<?php
$log = file_get_contents(__DIR__.'/storage/logs/import_activity.log');
$created = preg_match_all('/"action":"created"/', $log, $m);
$updated = preg_match_all('/"action":"updated"/', $log, $m2);
echo "created: " . ($created ?: 0) . "\n";
echo "updated: " . ($updated ?: 0) . "\n";
