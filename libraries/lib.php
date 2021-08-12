<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><?php
function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if(!$length) {
        return true;
    }
    return substr($haystack, -$length) === $needle;
}
function cleanFilename($stuff) {
	$illegal = array(" ","?","/","\\","*","|","<",">",'"');
	// legal characters
	$legal = array("-","_","_","_","_","_","_","_","_");
	$cleaned = str_replace($illegal,$legal,$stuff);
	return $cleaned;
}
function removeScriptTags($html) {
	$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
	return $html;
}
function str_replace_first($from, $to, $content) {
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}
function scan_dir($dir) {
    $ignored = array();
    $files = array(); 
    foreach (scandir($dir) as $file) {
        if ($file[0] === '.') continue; 
        if (in_array($file, $ignored)) continue; 
        $files[$file] = filemtime($dir . '/' . $file);
    }
    arsort($files); 
    $files = array_keys($files); 
    return ($files) ? $files : false;
}
function contains($str, $arr) {
    foreach($arr as $a) {
        if (!(stripos($str, $a) === false)) return true;
    }
    return false;
}
function custom_substr_count($str, $arr) {
	$i = 0;
    foreach($arr as $a) {
        if (substr_count(strtoupper($str), strtoupper($a))) $i+=substr_count(strtoupper($str), strtoupper($a));
    }
    return $i;
}
?>