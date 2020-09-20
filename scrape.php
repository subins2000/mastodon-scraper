<?php
// Start config

$instance = 'https://mastodon.social';
$dump = 'dump.txt'; // Content will be appended
$limit = 1000;
$older_than_toot = ""; // Toot ID. Fetch toots after this. 

// End config

if ($older_than_toot !== "") {
  $extra = "&max_id=$older_than_toot";
} else {
  $extra = "";
}
$id = "";
$fetched = [];

while ($id !== "" && count($fetched) < $limit ) {
  $r = json_decode(file_get_contents("$instance/api/v1/timelines/public?local=true" . $extra), true);
  foreach($r as $item) {
    $id = $item['id'];

    if (in_array($id, $fetched)) {
      continue;
    }

    $data = "\n" . $item['id'] . "\n" . $item['content'] . "\n";
    file_put_contents($dump, $data, FILE_APPEND);


    $fetched[] = $id;
  }
  if ($id == "") {
    echo "Toots ended."
  } else {
    echo "Fetched " . count($fetched) . " toots. Last fetched toot ID - $id" . PHP_EOL;
  }
  // older than
  $extra = "&max_id=" . $id;
}
echo "Fetched " . count($fetched) . " toots." . PHP_EOL;