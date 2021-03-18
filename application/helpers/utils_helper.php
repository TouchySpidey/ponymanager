<?php

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
