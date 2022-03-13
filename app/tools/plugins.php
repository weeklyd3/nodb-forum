<?php
require 'header.php';
if (isset($_POST['uninstall'])) {
	delTree(__DIR__ . '/../../extensions/' . cleanFilename($_POST['name']));
	$pluginJSON = json_decode($_POST['pluginJSON']);
	?><center>
		<img src="img/icons/CheckIcon.png" alt="" />
		<b><?php echo htmlspecialchars($pluginJSON->name); ?></b> has been uninstalled. If this was in error, please <a href="<?php echo htmlspecialchars($pluginJSON->url); ?>">visit its site</a> to re-install it.
	</center><?php
}
?>
<h2>Plugin Manager</h2>
<p>Here you can review info, get updates, and remove plugins.</p>
<p>To add plugins, please upload the plugin's folder (typically its name) to the <code>extensions</code> directory.</p>
<h3>Installed Plugins</h3>
<div class="flex">
<?php 
$plugins = array_diff(scandir(__DIR__ . '/../../extensions/', SCANDIR_SORT_NONE), array('.', '..', 'readme.txt'));
if (count($plugins) === 0) {
	?>No plugins installed.<?php
}
foreach ($plugins as $plugin) {
	$pluginconfig = json_decode(file_get_contents(__DIR__ . '/../../extensions/' . $plugin . '/config.json'));
	?>
	<div>
		<h4><?php echo htmlspecialchars($pluginconfig->name); ?></h4>
	<p><?php echo htmlspecialchars($pluginconfig->description); ?></p>
	<p><a href="app/tools/plugininfo.php?dirname=<?php echo htmlspecialchars(urlencode($plugin)); ?>">details</a></p>
	</div><?php
}
?></div>
<style>
	.flex {
		display: flex;
		flex-wrap: wrap;
	}
	.flex div {
		border: 1px solid;
		margin-right: 7px;
		margin-bottom: 7px;
		margin: 5px;
		border-radius: 7px;
		-webkit-box-shadow: 5px 5px 5px 5px #888888; 
box-shadow: 5px 5px 5px 5px #888888;
	}
</style>