<?php
/*
 * get/index.php
 * 
 * Copyright 2015 Cory Redmond <acecheese@live.co.uk>
 * 
 * This program is strictly to stay on the allocated server
 * unless strict permission from Cory Redmond himself is given.
 * 
 * http://www.spigotmc.org/members/cory-redmond.4411/
 * 
 * 
 */
 
	function getURL($url, $print){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result=curl_exec($ch);
		http_response_code(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		curl_close($ch);
		if($print) echo($result);
		return $result;
	}
	
	function printURL($url){ getURL($url, true); }
	
	function getURLWithData($url, $data, $print){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,
				array(
					"Content-type: application/json",
					"Content-Length: " . strlen($data))
				);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result=curl_exec($ch);
		http_response_code(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		curl_close($ch);
		if($print) echo($result);
		return $result;
		
	}
 
	header('content-type: text/plain');

	//if($_SERVER['REMOTE_ADDR'] != "37.187.112.26" && $_SERVER['REMOTE_ADDR'] != "198.100.147.96"){
	//	die($_SERVER['REMOTE_ADDR'] . " - You are not allowed to view this page!");
	//}
	
	if(!isset($_GET['mode']) || empty($_GET['mode'])){
		die("Invalid request!");
	}
		
	$mode = $_GET['mode'];
		
	//Are you here?!
	if(strtoupper($mode) == strtoupper("hello")){
		echo("hi_-_" . hash_file('md5', __FILE__));
		return;
	}
	
	//Username to profile.
	// http://addr.whatever/get/?mode=un2p&user=<USER>
	if(strtoupper($mode) == strtoupper("UN2P")){
		
		if(!isset($_GET['user']) || empty($_GET['user'])){
			die("Invalid request!");
		}
		
		$url = "https://api.mojang.com/users/profiles/minecraft/" . $_GET['user'];
		
		//if(isset($_GET['time']) && !empty($_GET['time'])){
		//	$url = $url . "?at=" . $_GET['time'];
		//}
		
		printURL($url);
		return;	
	}
	
	//UUIDs to name history.
	// http://addr.whatever/get/?mode=un2p&uuid=<UUID>[?time=<TIMESTAMP>]
	if(strtoupper($mode) == strtoupper("U2NH")){
		
		if(!isset($_GET['uuid']) || empty($_GET['uuid'])){
			die("Invalid request!");
		}
		
		$url = "https://api.mojang.com/user/profiles/" . str_replace("-", "", $_GET['uuid']) . "/names";
		
		printURL($url);
		return;
		
	}
	
	//Player names 2 uuids.
	// http://addr.whatever/get/?mode=un2u <POST DATA>
	if(strtoupper($mode) == strtoupper("un2u")){
		
		if(!isset($_POST['profileNames']) || empty($_POST['profileNames'])){
			die("Invalid request!");
		}
		
		$url = "https://api.mojang.com/profiles/minecraft";
		
		getURLWithData($url, $_POST['profileNames'], true);
		return;
	}
	
	//UUID 2 Profile + Properties
	// http://addr.whatever/get/?mode=u2p&uuid=<UUID>
	if(strtoupper($mode) == strtoupper("u2p")){
		
		if(!isset($_GET['uuid']) || empty($_GET['uuid'])){
			die("Invalid request!");
		}
		
		$url = "https://sessionserver.mojang.com/session/minecraft/profile/" . str_replace("-", "", $_GET['uuid']);
		
		printURL($url);
		return;
	}
	
	die("Incorrect mode?");

?>
