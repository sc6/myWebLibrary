<?php

//Server page originally created by Steve Chang (github:steveisreally)
//Please see the attached README instructions for usage.
//Prints out the number of users who have recently pinged this page.
//This page can be AJAX'd from a number of sources and is thread-safe.
//A "person" in this context is defined as a unique IP address.

//This page may do a variety of things, such as pulling data from a database.
//However, this specific server provides information on how many people have
//recently pinged this page.

//TODO: If lock could not be obtained, implement a "retry."

$ip = $_SERVER['REMOTE_ADDR'];
$time = time();
$update = "";
$numberOfPeople = 0;

$filename = "./activeVisitors.txt";
$fsize = filesize("./activeVisitors.txt");
$handle = fopen($filename, "rb");

if($fsize == 0) {									//edge case: blank file, just add myself and I'm done
	$update .= "$ip#$time;\n";
	$numberOfPeople = 1;
}
else if(flock($handle, LOCK_EX)) {					// acquire an exclusive lock (requires update on current data)
	$contents = fread($handle, $fsize);
	$contents = explode(";\n", $contents);			//gets all ip-timestamp pairs in log file
	$tuples = array();

	foreach($contents as $ip_timestamp) {			//separates ips and timestamps into k-v pairs
		if(strcmp($ip_timestamp, "") === 0) break;
		$temp = explode("#", $ip_timestamp);
	}
	
	$tuples[$ip] = $time;							//if ip exists, update time; otherwise, create a new entry.
	
	foreach($tuples as $key => $value) {			//expired visitor, so update tuples array
		if($value < ($time - 10)) {
			unset($tuples[$key]);					
		}
	}
	
	foreach($tuples as $key => $value) {			//convert tuples array to saveable text to log
		$update .= "$key#$value;\n";
	}
	
	$numberOfPeople = count($tuples);				//counts and saves number of people in tuples array
	
	$handle = fopen($filename, "wb");				//write and save to log file
	//ftruncate($handle, 0); -why doesn't this work? (produces null characters)
	fwrite($handle, $update);
	fflush($handle);
	flock($handle, LOCK_UN);
	
	fclose($handle);								//releases exclusive lock
	
} else {
    echo "Couldn't get an exclusive lock";
}

echo "Currently $numberOfPeople ". ($numberOfPeople==1 ? "person" : "people" ) ." viewing this page.";

