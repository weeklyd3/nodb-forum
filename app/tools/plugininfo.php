<?php
require 'header.php';
if (!isset($_GET['dirname'])) die("No plugin name specified.");
$plugin = cleanFilename($_GET['dirname']);
if (!is_dir(__DIR__ . '/../../extensions/' . $plugin)) die("Bad plugin name");
$pluginconfig = json_decode(file_get_contents(__DIR__ . '/../../extensions/' . $plugin . '/config.json'));
?>
<h2>Plugin: <?php echo htmlspecialchars($pluginconfig->name); ?></h2>
<p><?php echo htmlspecialchars($pluginconfig->description); ?></p>
<?php 
if (isset($_GET['options'])) {
	if (!file_exists(__DIR__ . '/../../extensions/' . $plugin . '/config.php')) {
		?>There are no options for this plugin.<?php
	} else {
		require __DIR__ . '/../../extensions/' . $plugin . '/config.php';
	}
	?><p><a href="app/tools/plugininfo.php?dirname=<?php echo htmlspecialchars(urlencode($plugin)); ?>">Close options</a></p><?php
	exit(0);
} ?>
<ul>
	<li><a href="<?php echo htmlspecialchars($pluginconfig->url); ?>" target="_blank">Open site</a></li>
	<li><a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>&options=true">Configure options</a></li>
	<li><a href="app/tools/pluginremove.php?dirname=<?php echo htmlspecialchars(urlencode($plugin)); ?>"><img src="img/icons/XIcon.png" alt="" /> Uninstall</a></li>
	<li><a href="app/tools/plugins.php">Back to Plugin Manager</a></li>
</ul>