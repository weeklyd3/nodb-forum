<?php include_once('header.php');
?>
<h2>Ban Users</h2>
<ul>
	<li><a href="app/tools/users.php">&raquo; User Removal</a></li>
	<li>&raquo; User Bans</li>
	<li><a href="app/tools/unban.php">&raquo; User Unbans</a></li>
</ul>
<a href="app/tools/unban.php">Unban users &raquo;</a>
<p>You can't ban yourself, so your name has been hidden from this list.</p>

<?php
	if (isset($_POST['reason'])) {
		echo '<ul>';
		foreach ($_POST as $key => $value) {
			if ($key != 'reason') {
				$handle = fopen('../../data/accounts/'.cleanFilename($value).'/ban.txt', 'w+');
				$status = fwrite($handle, htmlspecialchars($_POST['reason']));
				if ($status) {
					echo '<li>User '.htmlspecialchars($value).' banned.</li>';
				} else {
					echo '<li>User '.htmlspecialchars($value).' had trouble being banned.</li>';
				}
			}
		}
		echo '</ul>';
	}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Ban reason: <input required="required" type="text" name="reason" list="reasons" size="50" /></label>

<input type="submit" value="Ban" /><br />
<datalist id="reasons">
	<option value="TOS violation"></option>
	<option value="Unreasonable amount of abuse"></option>
	<option value="To cool down"></option>
	<option value="Too much spam"></option>
</datalist>
<table>
<tr><th>Check</th><th>Name</th></tr>
<?php
if ($handle = opendir('../../data/accounts')) {
    while (false !== ($entry = readdir($handle))) {
		if ($entry!='.' && $entry!='..' && is_dir('../../data/accounts/'.$entry) && !file_exists('../../data/accounts/'.$entry.'/ban.txt') && $entry != getname()) {
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