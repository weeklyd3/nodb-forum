<?php include_once('header.php'); 
$config = json_decode(file_get_contents('../../config.json'));
?>
<h2>Admins</h2>
<p>Admins have more power than normal users. <a href="faq.php#admins">See the FAQ for a list of admin powers.</a></p>
<p>If you want to grant these privileges to a user, or revoke the privileges from someone who is not behaving responsibly, please fill out the form below.</p>
<p>Note: If you are removing an administrator for disruptive violations that are unacceptable, please also ban or warn the user.</p>
<?php 
	function changeAdmin(&$config) {
		if (isset($_POST['confirm'])) {
			if ($_POST['action'] == 'add') {
				if (!in_array($_POST['name'], $config->admins)) {
					$message = new stdClass;
					$message->time = time();
					$message->type = "User status changed";
					$message->text = "You have become an admin. Woohoo! Click to check out the mod dashboard you just gained access to.";
					$message->url = $_SERVER['PHP_SELF'];
					$message->read = false;
					$j = json_decode(file_get_contents("../../data/accounts/" . cleanFilename($_POST['name']) . '/inbox.json'));
					array_push($j->items, $message);
					fwrite(fopen("../../data/accounts/" . cleanFilename($_POST['name']) . '/inbox.json', 'w+'), json_encode($j));
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
			<p>By clicking "Yes" below, you agree to the following:</p>
			<div class="smaller box">
				<p>Important note: Please ignore this message when you are debugging.</p>
				<p>(1) General Info: You agree to the following:</p>
				<ul>
				<li>(a) That you are <?php echo htmlspecialchars($_POST['action']) == "add" ? "adding" : "removing"; ?> this user to/from the list of administrative users in good faith, and manually (i.e. not with a bot, script, or web automation tool of any kind), </li>
				<li>(b) That this user will <?php if ($_POST['action'] === 'rm') { ?>no longer <?php } ?>have access to the moderator dashboard and other administrative privileges, and that</li>
				<li>(c) You understand that this action will have consequences, both intended and unintended.</li>
				</ul>
				<p>(2) If Adding</p>
				<p>If you are adding this administrator, you agree that:</p>
				<ul>
				<li>(a) this user has shown a record of exceptional behavior within the past few years,</li>
				<li>(b) this user has been on the site long enough to understand the rules and policies,</li>
				<li>(c) this user agrees to continue abiding by these policies and enforcing it to the best of his/her ability,</li>
				<li>(d) this user has not been banned or warned in the past few years, and</li>
				<li>(e) this user has requested adminship through a private message that was sent manually or after a request to apply was sent to the account, and within a year of loading this page.</li>
				</ul>
				<p>(3) If Removing</p>
				<p>If you are removing an administrator, you agree:</p>
				<ul>
				<li>(a) EITHER: that this user has violated the policies of this site in such a way that indicates the user must be immediately blocked from re-performing this action,</li>
				<li>(a) OR: this user has been warned adequately to allow room for improvement, but has failed to improve in behavior over the course of being active during a few months,</li>
				<li>(b) that the user has been notified about his or her upcoming change in user status,</li>
				<li>(c) that you are removing this administrator for a reasonable reason that can be proved with material evidence when questioned,</li>
				<li>(d) that you will tell the true reasons why you have removed this administrator if you are ever questioned, and</li>
				<li>(e) that you are not removing this administrator for any opiniated reason or personal hatred.</li>
				</ul>
				<p>Nice job! You've finished reading the policy. If you agree with all the statements and are sure that you want to perform this action, press <kbd>Yes</kbd>. Otherwise, press <kbd>No</kbd>.</p>
			</div>
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