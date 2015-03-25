<?php

namespace Mylib;

class Util {

	public static function normal2br($input) {
		$beReplace = array("\n", "\s", "\t");
		$toReplace = array("<br/>", "&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		return str_replace($beReplace, $toReplace, $input);						
	}
	
	public static function purify($input) {
		$beReplace = array("<", ">");
		$toReplace = array("&lt;", "&gt;");
		return str_replace($beReplace, $toReplace, $input);								
	}
	
	public static function isFileValid($name) {
		if (!\Input::hasFile($name)) {
			\Log::info('no file upload');
			return false;
		}
		if (!\Input::file($name)->isValid()) {
			\Log::warning('invalid file upload');
			return false;
		}
		return true;
	}
	public static function writeDataUrlToFile($url, $filePath) {
		list($type, $data) = explode(';', $url);
		list(, $data) = explode(',', $data);
		$data = base64_decode($data);		
		file_put_contents($filePath, $data);
	}
	
	public static function humanFilesize($bytes, $decimals = 2) {
	    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
	    $factor = floor((strlen($bytes) - 1) / 3);
	    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}
	
}
