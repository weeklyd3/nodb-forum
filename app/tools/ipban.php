<?php 
require 'header.php';
?>
<h2>Active IP Bans</h2>
<div class="box">
	<table class="table exempt-from-format">
		<tr>
		<th>Banned IP</th>
		<th>Ban Date</th>
		<th>Reason</th>
		<th>Can logged in users from this IP post?</th></tr>
		<?php 
$path = '../../ipblock.json';
if (!file_exists($path)) {
	fwrite(fopen($path, 'w+'), '{}');
}
$blockedIPS = file_get_contents($path);
$blockedIPS = (array) json_decode($blockedIPS);
if (isset($_POST['confirm'])) {
	$IPS2Block = explode(" ", $_POST['ip']);
	foreach ($IPS2Block as $ip) {
		$block = new stdClass;
		$block->reason = $_POST['reason'];
		$block->blockExistingAccounts = isset($_POST['blockExistingAccounts']);
		$block->date = time();
		$blockedIPS[$ip] = $block;
	}
	fwrite(fopen($path, 'w+'), json_encode((object) $blockedIPS));
}
if (isset($_POST['confirmu'])) {
	$IPS2Unblock = explode(" ", $_POST['unblock']);
	foreach ($IPS2Unblock as $ip) {
		unset($blockedIPS[$ip]);
	}
fwrite(fopen($path, 'w+'), json_encode((object) $blockedIPS));
}
if (count($blockedIPS) === 0) {
	?><tr>
		<td colspan="4">No IP addresses are currently banned. You can ban IP addresses that are only being disruptive without no positive contributions.</td>
	</tr><?php
} 
	foreach ($blockedIPS as $ip => $ban) {
		?><tr>
			<td><?php echo $ip; ?></td>
			<td><?php echo friendlyDate($ban->date); ?></td>
			<td><?php echo htmlspecialchars($ban->reason); ?></td>
			<td><?php echo $ban->blockExistingAccounts ? "No" : "Yes"; ?></td>
		</tr>
		<?php
	}
	?>
	</table></div>
<h2>Ban an IP address</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Enter IP addresses (separated by spaces): <input type="text" name="ip" /></label>
<p class="smaller">For example, typing <code>192.0.2.16 <?php echo $_SERVER['REMOTE_ADDR']; ?></code> will block <code>192.0.2.16</code> and <code><?php echo $_SERVER['REMOTE_ADDR']; ?></code>.</p>
<label>Ban reason: <input type="text" name="reason" /></label><br />
<label><input type="checkbox" name="blockExistingAccounts" /> Block logged-in users from this IP address from posting</label>
<ul>
	<li>If <strong>the above is checked</strong>, account creation will be disabled, and logged-in users will not be able to post content.</li>
	<li>If the above is <strong>not</strong> checked, account creation will be blocked, but unblocked logged-in users will be able to post content.</li>
</ul>
<input type="submit" value="Ban IPs" class="brightred" name="confirm" /> 
</form>
<h2>Unblock an IP address</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<label>IP addresses to unblock (split by spaces): <input name="unblock" /></label><br />
	<input type="submit" name="confirmu" value="Unblock" />
</form>