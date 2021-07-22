<?php include('header.php'); ?>
<h2>Files</h2>
<ul>
<?php 
$role = $_POST['role'];
if ($role == 'delete') {
	foreach( $_POST as $stuff ) {
		if ($stuff != 'delete' && file_exists('../../files/uploads/'.$stuff)) {
			$unlink = unlink('../../files/uploads/'.$stuff);
			if ($unlink) {
				echo '<li>'.htmlspecialchars($stuff).' deleted!</li>';
			} else {
				echo '<li>'.htmlspecialchars($stuff).' not deleted!</li>';
			}
		}
	}
}
if ($role == 'chmod') {
	foreach( $_POST as $stuff ) {
		if ($stuff != 'chmod' && file_exists('../../files/uploads/'.$stuff)) {
			$int = intval('0'.$_POST['ch1'].$_POST['ch2'].$_POST['ch3']);
			$mod = chmod('../../files/uploads/'.$stuff, $int);
			if ($mod) {
				echo '<li>'.htmlspecialchars($stuff).' changed!</li>';
			} else {
				echo '<li>'.htmlspecialchars($stuff).' not changed!</li>';
			}
		}
	}
}
?>
</ul>
This is a list of uploaded files by users. Beware that 
deleting files will break the download links! (You cannot delete sample.txt)
<script>
function select($text) {
	var $checkboxes = document.querySelectorAll('input[type="checkbox"]');
	for (var $i = 0; $i < $checkboxes.length; $i++) {
		$checkboxes[$i].checked = $text;
	}
}
</script>
<br />
<a href="javascript:;" onclick="select('checked');">select all</a> | <a href="javascript:;" onclick="select('');">clear all</a>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" id="role" name="role" />
<input type="submit" value="Delete" onclick="document.getElementById('role').value='delete';" />
<input type="number" required="required" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="7" name="ch1" min="0" max="7" />
<input type="number" required="required" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="7" name="ch2" min="0" max="7" />
<input type="number" required="required" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="7" name="ch3" min="0" max="7" />
<input type="submit" value="CHMOD" onclick="document.getElementById('role').value='chmod';" />
<table>
<tr><th>Check</th> <th>Name</th> <th>Created (Windows) or Modified (Others)</th> <th>Size (bytes)</th> <th>View</th> <th>Download</th> <th>Permissions</th></tr>
<?php
if ($handle = opendir('../../files/uploads')) {
    while (false !== ($entry = readdir($handle))) {
		if ($entry!='.' && $entry!='..' && $entry != 'sample.txt') {
			echo '<tr><td><input type="checkbox" name="'.htmlspecialchars($entry).'" value="'.htmlspecialchars($entry).'" id="'.htmlspecialchars($entry).'" /></td><td><label for="'.htmlspecialchars($entry).'">';
			echo $entry;
			echo "\n";
			echo '</label></td><td>';
			$timestamp = filectime('../../files/uploads/'.$entry);
			echo gmdate("Y-m-d H:i:s", $timestamp);
			echo '</td><td>';
			echo strlen(file_get_contents('../../files/uploads/'.$entry));
			echo '</td><td>';
			echo '<a href="../../files/uploads/'.$entry.'" target="_blank">View</a>';
			echo '</td><td>';
			echo '<a href="../../files/uploads/'.$entry.'" download="">Download</a>';
			$perm = fileperms('../../files/uploads/'.$entry) & 07777;
			echo '</td><td>'.$perm.'</td></tr>';
		}
    }
    closedir($handle);
}
?>
<tr><th>Check</th> <th>Name</th> <th>Created (Windows) or Modified (Others)</th> <th>Size (bytes)</th> <th>View</th> <th>Download</th> <th>Permissions</th></tr>
</table>