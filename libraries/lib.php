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
if (!class_exists('emptyClass')) {
	class emptyClass {
		function _copyright() {
			return "    Forum Software\n    Copyright (C) 2021 contributors\n\n    This program is free software: you can redistribute it and/or modify\n    it under the terms of the GNU Affero General Public License as published by\n    the Free Software Foundation, either version 3 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    GNU Affero General Public License for more details.\n\n    You should have received a copy of the GNU Affero General Public License\n    along with this program.  If not, see <https://www.gnu.org/licenses/>.";
		}
	}
}
function endsWith(string $haystack, string $needle): bool {
    $length = strlen($needle);
    if(!$length) {
        return true;
    }
    return substr($haystack, -$length) === $needle;
}
function startsWith(string $haystack, string $needle): bool {
	$len = strlen($needle);
	return substr($haystack, 0, $len) === $needle;
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
function scan_dir(string $dir, string $fileToCount = ''): ?array {
    $ignored = array();
    $files = array(); 
    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') continue; 
        if (in_array($file, $ignored)) continue; 
        $files[$file] = filemtime(
			is_dir($dir . '/' . $file) ?
			"$dir/$file/$fileToCount" : "$dir/$file" 
		);
    }
    arsort($files); 
    $files = array_keys($files); 
    return ($files) ? $files : false;
}
function contains($str, $arr) {
    foreach($arr as $a) {
        if (stripos($str, $a) !== false) return true;
    }
    return false;
}
function custom_substr_count($str, $arr) {
	$i = 0;
    foreach($arr as $a) {
        $i += substr_count(strtoupper($str), strtoupper($a));
    }
    return $i;
}
function getname() {
	if (isset($_COOKIE['login'])) {
		$COOK = $_COOKIE['login'];
		$STATS = explode("\0", $COOK);
		$path = cleanFilename($STATS[0]);
		$path = __DIR__ . '/../data/accounts/'.$path;
		$hash = file_get_contents($path . '/psw.txt');
		if ($COOK != '') {
			if (password_verify($STATS[1], $hash)) {
				$match = true;
			} else {
				?></table><?php
				require_once __DIR__ . '/../invalidpass.php';
				chdir(__DIR__);
				exit(0);
			}
			return $STATS[0];
		}
	}
}
function custom_stripos(string $haystack, array $needle) {
	$record = strlen($haystack) + 1;
	foreach ($needle as $entry) {
		$currentpos = stripos($haystack, $entry);
		if ((is_numeric($currentpos) && $currentpos < $record) || !isset($record)) {
			$record = $currentpos;
		}
	}
	return $record;
}
function verifyAdmin() {
	$config = json_decode(file_get_contents(__DIR__ . '/../config.json'));
	return in_array(getname(), $config->admins);
}
function getDirSize(string $dir): ?int {
	$files = array_diff(scandir($dir), array('.', '..'));
	$size = 0;
	foreach ($files as $file) {
		if (is_dir($dir . '/' . $file)) {
			$size += getDirSize($dir . '/' . $file);
		} else {
			$filesize = filesize($dir . '/' . $file);
			if ($filesize === false) {
				return false;
			} else {
				$size += $filesize;
			}
		}
	}
	return $size;
}
function delTree($dir) { 
	$files = array_diff(scandir($dir), array('.', '..')); 

	foreach ($files as $file) { 
		(is_dir("$dir/$file") && !is_link("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	}

	return rmdir($dir); 
} 
function friendlyDate(int $timestamp) {
	$friend   = array();
	// for the date given...
	$year     = (int) date("Y", $timestamp);
	$month    = (int) date("m", $timestamp);
	$day      = (int) date("d", $timestamp);
	$hour     = (int) date("H", $timestamp);
	$minute   = (int) date("i", $timestamp);
	$second   = (int) date("s", $timestamp);
	// now... = (int) <-- to keep the column
	$time     = (int) time();
	$nyear    = (int) date("Y", $time);
	$nmonth   = (int) date("m", $time);
	$nday     = (int) date("d", $time);
	$nhour    = (int) date("H", $time);
	$nminute  = (int) date("i", $time);
	$nsecond  = (int) date("s", $time);

	$months = array(
		"Jan",
		"Feb",
		"Mar",
		"Apr",
		"May",
		"Jun",
		"Jul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec",
	);

	if ($month != $nmonth || $day != $nday) {
		array_push($friend, $months[$month - 1]);
		array_push($friend, $day);
	} 
	
	if ($year != $nyear) {
		if ($nyear - $year < 100) {
			array_push($friend, "'" . substr((string) $year, -2));
		} else {
			array_push($friend, $nyear);
		}
	}

	if ($month != $nmonth || $day != $nday) {
		array_push($friend, "@");
	} 

	$newminute = str_repeat("0", 2 - strlen((string) $minute)) . $minute;

	$newsecond = str_repeat("0", 2 - strlen((string) $second)) . $second;

	$times = $hour . ":" . $newminute . ":" . $newsecond;

	array_push($friend, $times);

	return implode(" ", $friend);
}
function dateDiff(int $before, int $after = time) {
	$bef = new DateTime('now');
	$now = new DateTime('now');
	$bef->setTimestamp($before);
	$now->setTimestamp($after);
	$dff = $now->diff($bef, true);
}
function colorNum(int $num): string {
	$number = $num;
	if ($num < 5) {
		return '<span>' . $num . '</span>';
	}
	if ($num >= 5 && $num < 15) {
		return '<span style="color:#a7510c;">' . $num . '</span>';
	}
	if ($num >= 15 && $num < 100) {
		return '<span style="color:#da680b;">' . $num . '</span>';
	}
	if ($num >= 100 && $num < 1000) {
		return '<span style="color:#f48225;">' . $number . '</span>';
	}
	if ($num >= 1000) {
		if ($num >= 1000) {
			$number = round(($num / 1000), 1) . 'k';
		}
		if ($num >= 1000000) {
			$number = round(($num / 100000), 1) . 'm';
		}
		return '<span style="color:#f48225;">' . $number . '</span>';
	}
}
function copyDir(string $dir, string $dest, array $ignored = array(), int $t = 0, bool $header = true): ?string {
	if (is_dir($dest)) {
		if (count(scandir($dest, SCANDIR_SORT_NONE)) == 2) {
			return null;
		}
	} else {
		$m = mkdir($dest, 0777, true);
		if ($m === false) return null;
	}
	$scan = scandir($dir);
	$count = count($scan) - 2;
	$i = 0;
	if ($header) {
		$log = array("File copy", "  from " . $dir, "  to " . $dest, "Started at " . date("H:i m/d/Y"));
	} else {
		$log = array();
	}
	foreach ($scan as $file) {
		$i++;
		if (in_array($file, array('.', '..'))) {$i--; continue;}
		if (in_array($file, $ignored)) {
			$logMsg = "$i of $count: ignored";
			continue;
		}
		$logMsg = str_repeat(" ", $t) . "Copying " . $i . " of " . $count;
		error_log($logMsg);
		array_push($log, $logMsg);
		if (is_dir("$dir/$file")) {
			$s = copyDir("$dir/$file", "$dest/$file", array(), $t + 4, false);
			if ($s === false) return null;
			array_push($log, $s);
		} else {
			$s = copy("$dir/$file", "$dest/$file");
			if ($s === false) return null;
		}
	}
	return implode("\n", $log);
}
function recListDIR(string $dir) {
	$s = array_diff(scandir($dir, SCANDIR_SORT_NONE), array('.', '..'));
	foreach ($s as $f) {
		?><details><summary><?php
		echo htmlspecialchars($f);
		if (is_dir("$dir/$f")) {
			echo "/</summary>";
			recListDIR("$dir/$f");
		} else {
			?></summary><pre><code><?php 
			echo htmlspecialchars(file_get_contents("$dir/$f")); ?></code></pre><?php
		}
		?></details><?php
	}
}
function restore(string $bkdir, string $target): ?string {
	if (!is_dir($bkdir)) return null;
	if (!is_dir($target)) mkdir($target, 0777, true);
	foreach (scandir($bkdir) as $f) {
		if (in_array($f, array('.', '..'))) continue;
		if (!is_dir("$bkdir/$f")) {
			$c = file_get_contents("$bkdir/$f");
			fwrite(fopen("$target/$f", "w+"), $c);
		} else {
			restore("$bkdir/$f", "$target/$f");
		}
	}
	return true;
}
function startsWithNumber(string $str) {
    return preg_match('/^\d/', $str) === 1;
}
function donothing() {
	return;
}
function random(array $options) {
	shuffle($options);
	return $options[array_rand($options)];
}
?>