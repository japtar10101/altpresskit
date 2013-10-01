<?php

class LoadHelper {

/*
	simpleCachedCurl V1.1
	Dirk Ginader
	ginader.com

	code: http://github.com/ginader

	easy to use cURL wrapper with added file cache

	usage: created a folder named "cache" in the same folder as this file and chmod it 777
	call this function with 3 parameters:
		$url (string) the URL of the data that you would like to load
		$expires (integer) the amound of seconds the cache should stay valid
		$debug (boolean, optional) write debug information for troubleshooting
		
	returns either the raw cURL data or false if request fails and no cache is available

	*/
	public static function loadCached($url, $expires, $debug = false){
		if (!function_exists('curl_version')){
			ErrorHelper::logWarning('Could not fetch data, cURL unavailable');
			return null;
		}
		
		$filename = LoadHelper::getFilename($url);
		
		if (!LoadHelper::hasCache($url, $expires)) {
			if ($debug) ErrorHelper::logDebug('No cache or expired, making new request');

			$rawData = LoadHelper::load($url);
			
			if (!$rawData) {
				if ($debug) ErrorHelper::logDebug('cURL request failed:' . $error);
				if ($changed != 0){
					if($debug) ErrorHelper::logDebug('Using expired cache');
					$cache = file_get_contents($filename);
					return $cache;
				} else {
					if ($debug) ErrorHelper::logDebug('Request failed, no cache');
					return false;
				}
			}

			if ($debug) ErrorHelper::logDebug('Data returned, saving it to cache');

			$cache = fopen($filename, 'wb');
			$write = fwrite($cache, $rawData);

			if ($debug && !$write) ErrorHelper::logDebug('Writing to ' . $filename . ' failed. Make sure the folder ' . dirname(__FILE__) . '/cache/ is writeable (chmod 777)');

			fclose($cache);
			return $rawData;
		}

		if ($debug) ErrorHelper::logDebug('Cache hit, using that');

		return file_get_contents($filename);
	}

	public static function hasCache($url, $expires){
		$filename = LoadHelper::getFilename($url);
		$changed = 0;

		if (!file_exists($filename)) return false;

		$changed = filemtime($filename);
		$now = time();
		$diff = $now - $changed;

		return $diff < $expires;
	}

	public static function load($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// we need to load custom https certificates here to be able to load from github securely
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/cert/cacert.pem');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$rawData = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		return $rawData;
	}

	public static function getFilename($url) {
		return LoadHelper::getCacheDir() . md5($url) . '.cache';
	}

	public static function getCacheDir() {
		return dirname(__FILE__).'/../cache/';
	}
}
?>