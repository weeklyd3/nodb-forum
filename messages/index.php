<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

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
	if (!isset($_COOKIE['login'])) {
		?><h2>Messages</h2><p>Send private messages to other people, without using other applications or installing any software.</p><p>Free to use for anyone who has an account.</p><?php
	} else {
		?>	<br /><div style="border-bottom:1px solid;">
		<a href="account/">Account Options</a>
		<span style="border-radius:3px 3px 0px 0px; text-decoration:none;border:1px solid;border-bottom:1px solid pink;">Private Messages</span>
	</div><h2>Inbox</h2><div><a href="messages/new.php">Write message</a></div>
		<table width="100%" class="table">
			<tr><th>From</th><th>Subject</th><th>Preview</th><th>View</th></tr>
			<?php 
				$r = array_reverse((array) json_decode(file_get_contents(__DIR__ . '/msg.json')));
				foreach ($r as $n => $m) {
					if (in_array(getname(), $m->people)) {
						?><tr<?php 
							if (!in_array(getname(), $m->read)) {
								?>  style="font-weight:bold;"<?php
							}
						?>>
							<td><?php echo htmlspecialchars($m->from); ?></td>
							<td><?php echo htmlspecialchars($m->subject); ?></td>
							<td><?php 
								echo htmlspecialchars(substr(str_replace(array("\r", "\n"), '', $m->body), 0, 300));
							?></td>
							<td><a href="messages/viewmsg.php?id=<?php echo htmlspecialchars(urlencode($n)); ?>">View</a></td>
						</tr><?php
					}
				}
			?>
			<tr><th>From</th><th>Subject</th><th>Preview</th><th>View</th></tr>
		</table>
		<?php
	}
	include('../public/footer.php');
	?>