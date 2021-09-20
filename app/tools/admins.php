<?php include_once('header.php'); 
$config = json_decode(file_get_contents('../../config.json'));
?>
<h2>Admins</h2><?php 
	function changeAdmin(&$config) {
		if (isset($_POST['confirm'])) {
			if ($_POST['action'] == 'add') {
				if (!in_array($_POST['name'], $config->admins)) {
					array_push($config->admins, $_POST['name']);
				}
			}
			if ($_POST['action'] == 'rm') {
				$config->admins = array_values(array_diff($config->admins, array($_POST['name'])));
			}
			?><div>Wrote changes to disk</div><?php
		}
		if (!isset($_POST['c'])) return;
		if (!isset($_POST['action'], $_POST['name'])) {
			?><div>Not all fields were filled out.</div><?php 
			return;
		}
		if (!in_array($_POST['action'], array('add', 'rm'))) return;
		if (!(file_exists(__DIR__ . '/../../data/accounts/' . cleanFilename($_POST['name']) . '/user.txt'))) {
			?><div>Bad name</div><?php 
			return;
		}
		if ($_POST['name'] == getname()) {
			?><div>You can't change yourself.</div><?php 
			return;
		}
		if (!isset($_POST['confirm'])) {
			?><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<fieldset><legend>Confirm</legend>
			<p>Are you <strong>absolutely sure</strong>!?</p>
			<p>Action: <?php echo htmlspecialchars($_POST['action']); ?> admin "<?php echo htmlspecialchars($_POST['name']); ?>"</p>
			<?php foreach ($_POST as $key => $value) {
				if ($key == 'c') continue;
				?><input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" /><?php
			} ?>
			<input type="submit" name="confirm" value="Yes" />
			<input type="submit" onclick="window.location.href+='';" value="No" /></fieldset>
			</form><?php
			exit(0);
		}
	}
	changeAdmin($config);
	fwrite(fopen(__DIR__ . '/../../config.json', 'w+'), json_encode($config));
?><ul>
<?php 
	foreach ($config->admins as $admin) {
		?><li><?php echo htmlspecialchars($admin); ?></li><?php
	}
?></ul>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset><legend>Action:</legend>
<label><input type="radio" name="action" value="add" /> Add admin</label><br />
<label><input type="radio" name="action" value="rm" /> Remove admin</label></fieldset>
<label>Add or remove admin: <input type="text" name="name" /></label>
<br />
<input type="hidden" name="c" value="" />
<input type="submit" value="Go" />
</form>