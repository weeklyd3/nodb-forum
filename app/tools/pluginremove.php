<?php
require 'header.php';
if (!isset($_GET['dirname'])) die("No plugin name specified.");
$plugin = cleanFilename($_GET['dirname']);
if (!is_dir(__DIR__ . '/../../extensions/' . $plugin)) die("Bad plugin name");
$pluginconfig = json_decode(file_get_contents(__DIR__ . '/../../extensions/' . $plugin . '/config.json'));
?>
<h2>Uninstall <strong><?php echo htmlspecialchars($pluginconfig->name); ?></strong>?</h2>
<form action="app/tools/plugins.php" method="post">
	<input type="hidden" name="pluginJSON" value="<?php echo htmlspecialchars(json_encode($pluginconfig)); ?>" />
	<input type="hidden" name="name" value="<?php echo htmlspecialchars($_GET['dirname']); ?>" />
	<input type="submit" name="uninstall" value="Confirm Uninstall" />
</form>