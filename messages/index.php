<?php
/*
    Forum Software
    Copyright (C) 2022 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.


    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><html lang="en">
  <head>
    <title>My Mail</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
$GLOBALS['filterMethod'] = isset($_GET['filter']) ? $_GET['filter'] : "everything";
	if (!isset($_COOKIE['login'])) {
		?><h2>Messages</h2><p>Send private messages to other people, without using other applications or installing any software.</p><p>Free to use for anyone who has an account.</p><p>Register an account to send and receive mail.</p><?php
	} else {
		?>	<br /><div style="border-bottom:1px solid;">
		<a href="account/">Account Options</a>
		<span style="border-radius:3px 3px 0px 0px; text-decoration:none;border:1px solid;border-bottom:1px solid pink;">Private Messages</span>
	</div><h2>Private Messages</h2><div><a href="messages/new.php" class="fakebutton">
		New Message
	</a></div><br />
	  <div>
		  <?php 
			function check(string $name) {
				if ($GLOBALS['filterMethod'] === $name) {
					?> selected="selected"<?php
				}
			}
		?>
		  <form action="messages/" method="get">
			  <label>Messages to show:
			  <select name="filter">
				  <optgroup label="Everything">
					  <option value="everything"<?php check('everything'); ?>>Show everything</option>
				  </optgroup>
				  <optgroup label="Filter messages to show">
				  <option value="unread"<?php check('unread'); ?>>Show unread</option>
				  <option value="read"<?php check('read'); ?>>Show read</option>
				  </optgroup>
			  </select>
			  </label>
			  <input type="submit" value="Show!" />
		  </form>
	  </div>
		<table width="100%" class="messages table">
			<tr class="msgheader">
				<th>Date</th>
			<th>From</th><th>Message</th><th>View</th></tr>
			<?php 
		$GLOBLALS['mailShown'] = 0;
				if (!file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) {
					fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json', 'w+'), "{}");
				}
				$r = array_reverse((array) json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')));
				foreach ($r as $n => $m) {
					if ($GLOBALS['filterMethod'] !== 'everything') {
						if (count($m->read) > 0 && $GLOBALS['filterMethod'] === 'unread') continue;
						if (count($m->read) === 0 && $GLOBALS['filterMethod'] === 'read') continue;
					}
						$GLOBALS['mailShown']++;
					?><tr<?php 
						if (!in_array(getname(), $m->read)) {
							?> style="font-weight:bold;"<?php
						} else {
							?> style="color: #dddddd;"<?php
						}
					?>>
						<td><?php echo dateDiff($m->time, time()); ?></td>
						<td><?php 
					if (!in_array(getname(), $m->read)) {
						?><img src="img/icons/RedDotIcon.png" />  <?php
					}
					userlink($m->from); ?></td>
						<td><a href="messages/viewmsg.php?id=<?php echo htmlspecialchars(urlencode($n)); ?>"><div class="bold"><?php echo htmlspecialchars($m->subject); ?></div>
						<?php 
							echo htmlspecialchars(substr(str_replace(array("\r", "\n"), ' ', $m->body), 0, 30));
					if (strlen(str_replace(array("\r", "\n"), ' ', $m->body)) > 30) {
						?>...<?php
					}
						?></a></td>
						<td><a href="messages/viewmsg.php?id=<?php echo htmlspecialchars(urlencode($n)); ?>">View</a></td>
					</tr><?php
				}
		if (!$GLOBALS['mailShown']) {
			?><tr class="msgheader"><td colspan="3">
				<h3>You have no messages.</h3>
				<p>Try:</p>
				<ul>
					<li>Changing the filter option</li>
					<li>Participating in the site to receive messages</li>
					<li>Sending a message to someone else</li>
				</ul>
			</td></tr><?php
		}
			?>
			<tr class="msgheader"><th>Date</th><th>From</th><th>Message</th><th>View</th></tr>
		</table>
		<?php
	}
	include('../public/footer.php');
	?>
<style>
	.bold {
		font-weight: 700;
	}
	.big {
		font-size: 18px;
	}
	.messages tr:not(.msgheader):hover {
background-color: skyblue;
		color: black;
	}
</style>