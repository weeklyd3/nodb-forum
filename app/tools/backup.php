<?php 
include('header.php');
?>
<h2>Backup and Restore</h2>
<h3>Backup</h3>
<?php 
	if (isset($_POST['submit'])) {
		$a = copyDir(__DIR__ . '/../../', __DIR__ . '/../../backup/' . time(), array("backup"));
		logmsg("setting", "A backup was created", getname());
		?><label><br />Copy done. Log:<br /><textarea rows="25" style="width:100%;"><?php echo htmlspecialchars($a); ?></textarea><br /></label><?php
	}
?>
		<p>Click the button below to create a backup:</p>
<form action="app/tools/backup.php" method="post">
<input type="submit" value="Go now" name="submit" />
</form><?php 
if (!is_dir('../../backup')) exit('No backups. Make one?');
?>
<style>
.optionlink {
	display: none;
}
.optionlabel {
	width: 100%;
	display: block;
}
.optionlink:checked + label {
	background-color: blue;
	color: white;
	font-weight: bold;
}
.optionlink:checked + label::before {
	content: "✓\00a0";
}
.optionlink:not(:checked) + label::before {
	content: "\000BB\00a0";
}
</style>
<?php 
if (isset($_POST['restorethis'], $_POST['restore'], $_POST['action'])) {
	if ($_POST['action'] == 'preview') {
		?><h3>Preview of backup</h3><?php
		if (!is_dir(__DIR__ . '/../../backup/' . cleanFilename($_POST['restorethis']))) {
			?>Bad backup name<?php
		} else {
			recListDIR(__DIR__ . '/../../backup/' . cleanFilename($_POST['restorethis']));
		}
	} else {
		logmsg("setting", getname() . " restored the site from a saved backup at " . date("Y-m-d H:i:s", $_POST['restorethis']), getname());
		restore("../../backup/" . $_POST['restorethis'], "../../");
	}
}
?>
<h3>Restore or preview a backup</h3>
<form action="app/tools/backup.php" method="post">
	<fieldset>Select the backup from the times below.<legend>Backup:</legend>
	<?php 
		$s = scandir(__DIR__ . '/../../backup');
		natcasesort($s);
		foreach ($s as $b) {
			if (in_array($b, array('.', '..'))) continue;
			?><input type="radio" class="optionlink" value="<?php echo $b; ?>" id="<?php echo $b; ?>" name="restorethis" required="required" />
			<label class="optionlabel" for="<?php echo $b; ?>"><?php echo "Backup at " . friendlyDate((int) $b); ?></label><?php
		}
	?>
	</fieldset>
	<fieldset>Preview or restore?<legend>Action</legend>
	<input type="radio" class="optionLink" value="preview" name="action" id="one" />
	<label for="one" class="optionlabel">Preview</label>
	<input type="radio" class="optionLink" value="restore" name="action" id="two" checked="checked" />
	<label for="two" class="optionlabel">Restore</label></fieldset>
	<input type="submit" value="Go now" name="restore" />