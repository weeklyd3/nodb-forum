<?php include_once('header.php');
echo '<h2>Users</h2>';
?>
<ul>
	<li>&raquo; User Removal</li>
	<li><a href="app/tools/ban.php">&raquo; User Bans</a></li>
	<li><a href="app/tools/unban.php">&raquo; User Unbans</a></li>
</ul>
You may remove them here. <em>Do not remove users unless they seriously posted abusive content!</em>
<ul>
<?php 
foreach( $_POST as $stuff ) {
    if (file_exists('../../data/accounts/'.cleanFilename($stuff).'/user.txt')) {
		$rm = delTree('../../data/accounts/'.cleanFilename($stuff));
		if ($rm) {
			echo '<li>Account '.htmlspecialchars($stuff).' deleted.</li>';
		} else {
			echo '<li>Account '.htmlspecialchars($stuff).' not deleted.</li>';
		}
	}
}
?>
</ul>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<em>User removal cannot be undone!</em> Make up your mind on who to remove.<br />
<input type="submit" value="Remove Users" />
<hr />
<?php
$GLOBALS['i'] = 1;
if ($handle = opendir('../../data/accounts')) {

    while (false !== ($entry = readdir($handle))) {
		$address = '../../data/accounts/'.$entry;
        if ($entry != "." && $entry != ".." && is_dir('../../data/accounts/'.$entry)) {
			$string = '<input type="checkbox" id="'.$GLOBALS['i'].'" name="'.htmlspecialchars(file_get_contents($address.'/user.txt')).'" value="'.htmlspecialchars(file_get_contents($address.'/user.txt')).'" /><label for="'.$GLOBALS['i'].'">'.htmlspecialchars(file_get_contents($address.'/user.txt')).'</label>';
            echo "$string<hr />\n";
			$GLOBALS['i']++;
        }
    }

    closedir($handle);
}
?>
the end
<hr />
<input type="submit" value="Remove Users" />
</form>