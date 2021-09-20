<?php include_once('header.php');
?>
<h2>Unban Users</h2>
<ul>
	<li><a href="app/tools/users.php">&raquo; User Removal</a></li>
	<li><a href="app/tools/ban.php">&raquo; User Bans</a></li>
	<li>&raquo; User Unbans</li>
</ul>
<a href="app/tools/ban.php">&laquo; Back to Bans</a>
<?php
	if (isset($_POST['reason'])) {
		echo '<ul>';
		foreach ($_POST as $key => $value) {
			if ($key != 'reason') {
				$status = unlink('../../data/accounts/'.cleanFilename($value).'/ban.txt');
				if ($status) {
					echo '<li>User '.htmlspecialchars($value).' unbanned.</li>';
				} else {
					echo '<li>User '.htmlspecialchars($value).' had trouble being unbanned.</li>';
				}
			}
		}
		echo '</ul>';
	}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="submit" value="Unban" />
<input type="hidden" name="reason" value="value" />
<table>
<tr><th>Check</th><th>Name</th></tr>
<?php
if ($handle = opendir('../../data/accounts')) {
    while (false !== ($entry = readdir($handle))) {
		if ($entry!='.' && $entry!='..' && is_dir('../../data/accounts/'.$entry) && file_exists('../../data/accounts/'.$entry.'/ban.txt')) {
			$entry = file_get_contents('../../data/accounts/'.cleanFilename($entry).'/user.txt');
			echo '<tr><td>';
			echo '<input type="checkbox" id="';
			echo htmlspecialchars($entry);
			echo '"';
			echo ' name="';
			echo htmlspecialchars($entry);
			echo '" value="';
			echo htmlspecialchars($entry);
			echo '" />';
			echo '</td><td>';
			echo '<label for="';
			echo htmlspecialchars($entry);
			echo '">';
			echo htmlspecialchars($entry);
			echo '</label>';
			echo '</td></tr>';
		}
    }
    closedir($handle);
}
?>
</table>
</form>