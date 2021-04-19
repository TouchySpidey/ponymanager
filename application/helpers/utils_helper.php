<?php

function geocode($city, $address) {
	$response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address="
	. urlencode($city.','.$address)
	. "&key=".GOOGLE_SECRET_API);

	if ($response) {
		$json = JSON_decode($response, TRUE);
		if (isset($json['results']) && !empty($json['results'])) {
			$result = $json['results'][0];
			if (isset($result['geometry']['location'])) {
				$north = $result['geometry']['location']['lat'];
				$east = $result['geometry']['location']['lng'];
				return compact('north', 'east');
			}
		}
	}
	return false;
}
function distancematrix($origin, $destination) {
	$url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=decimal'
	.'&language=it'
	.'&origins='.$origin['north'].','.$origin['east']
	.'&destinations='.$destination['north'].','.$destination['east']
	.'&key='.GOOGLE_SECRET_API;
	$response = file_get_contents($url);
	if ($response) {
		$json = JSON_decode($response, true);
		if (isset($json['rows'][0]['elements'][0]['duration']['text'])) {
			return $json['rows'][0]['elements'][0]['duration']['text'];
		}
	}
	return false;
}
function debug(...$pieces) {
	echo "<pre style='white-space: pre-wrap; word-break: break-word;'>";
	foreach ((array) $pieces as $piece) {
		var_dump($piece);
	}
	echo "</pre>";
}

function utf8ize($d) {
	if (is_array($d)) {
		foreach ($d as $k => $v) {
			$d[$k] = utf8ize($v);
		}
	} else if (is_string ($d)) {
		return utf8_encode($d);
	} else if (is_nan($d) || is_infinite($d)) {
		return '';
	}
	return $d;
}

function generate_guid() {
	return sprintf("%08x-%08x-%08x-%08x", mt_rand(0, 0xFFFFFFFF), mt_rand(0, 0xFFFFFFFF), mt_rand(0, 0xFFFFFFFF), mt_rand(0, 0xFFFFFFFF));
}

function string_similarity($str1, $str2) {
	$str1 = strtoupper($str1);
	$str2 = strtoupper($str2);
	$pairs1 = [];
	for ($i = 0; $i < strlen($str1) - 1; $i++) {
		$pair = substr($str1, $i, 2);
		if (strpos($pair, ' ') === FALSE) {
			$pairs1[] = $pair;
		}
	}
	$pairs2 = [];
	for ($i = 0; $i < strlen($str2) - 1; $i++) {
		$pair = substr($str2, $i, 2);
		if (strpos($pair, ' ') === FALSE) {
			$pairs2[] = $pair;
		}
	}
	if (empty($pairs1) && empty($pairs2)) {
		return 0;
	}
	$union = count($pairs1) + count($pairs2);
	$intersection = 0;
	foreach ($pairs1 as $p1) {
		for ($i = 0; $i < count($pairs2) - 1; $i++) {
			if ($p1 == $pairs2[$i]) {
				$intersection++;
				array_splice($pairs2, $i, 1);
				break;
			}
		}
	}

	return 2 * $intersection / $union;
}
