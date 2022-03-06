<?php 
header("Content-Type: text/plain");
echo "Name";
$s = scandir("uploads/");
echo str_repeat(" ", max(array_map('strlen', $s)) - 4);
echo "   ";
$l = max(array_map('strlen', $s));
echo "Modified\n";
echo str_repeat("-", max(array_map('strlen', $s)));
echo "   ";
echo "----------";
echo "\n";
foreach ($s as $f) {
	if (in_array($f, array('.', '..'))) continue;
	echo htmlspecialchars($f);
	echo str_repeat(" ", $l - strlen($f));
	echo "   ";
	echo filemtime("uploads/$f");
	echo "\n";
}