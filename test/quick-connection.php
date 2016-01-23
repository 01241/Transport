<?php

use Transport\Entity\Query;
use Transport\Entity\Location\Station;
use Transport\Entity\Location\LocationQuery;
use Transport\Entity\Location\NearbyQuery;
use Transport\Entity\Schedule\ConnectionQuery;
use Transport\Entity\Schedule\StationBoardQuery;

require_once 'bootstrap.php';

// Location
$query = new LocationQuery(array('from' => 'Zürich', 'to' => 'Bern'));

$api = new Transport\API();
$stations = $api->findLocations($query);

$from = reset($stations['from']) ?: null;
$to = reset($stations['to']) ?: null;

// Connection
$date = new \DateTime('');
$query = new ConnectionQuery($from, $to, array(), $date->format('c'));

$xml = $api->sendQuery($query);

$filename = __DIR__ . '/fixtures/connections/hafas_response_' . $date->format('Y-m-d') . '.xml';
file_put_contents($filename, $xml->getContent());

// try to format
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
if ($dom->load($filename)) {
    $dom->save($filename);
}