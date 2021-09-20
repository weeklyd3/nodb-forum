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
    <title>Compose Message</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
  <style>.error{border:1px solid #FF0000; color: #FF0000; background-color:#ffcccb ;}</style>
	<?php 
class Message {
	public function __construct(string $subject, array $people, string $from, string $body) {
		$this->subject = $subject;
		$this->people = $people;
		$this->from = $from;
		$this->body = $body;
		$this->read = array();
		$this->time = time();
	}
}
		error_reporting(E_ALL);
		if (!file_exists(__DIR__ . '/msg.json')) fwrite(fopen(__DIR__ . '/msg.json', 'w+'), '{}');
		if (!isset($_COOKIE['login'])) die("Log in to write messages");
		function send() {
			if (isset($_POST['to'], $_POST['subject'], $_POST['body'])) {
				if ($_POST['subject'] == '') {
					?><div class="error">Subject missing, correct and re-send.</div><?php
					return;
				}
				if ($_POST['to'] == '') {
					?><div class="error">Recipients missing, correct and re-send.</div><?php
					return;
				}
				if ($_POST['body'] == '') {
					?><div class="error">No body</div><?php 
					return;
				}
				$rec = array_filter(array_unique(explode("\n", $_POST['to'])), function($e) {
					return file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(trim($e)) . '/psw.txt');
				});
				foreach ($rec as &$r) {
					$r = trim($r);
				}
				var_dump($rec);
				if (count($rec) === 0) {
					?><div class="error">All of the recipient names were invalid.</div><?php 
					return;
				}
				$title = getname() . time();
				foreach ($rec as $a) {
					$inbox = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename($a) . '/inbox.json'));
					$item = new stdClass;
					$item->time = time();
					$item->text = substr($_POST['body'], 0, 50);
					$item->read = false;
					$item->type = "New private message";
					if ($inbox->items == null) $inbox->items = array();
					$item->url = 'messages/viewmsg.php?id=' . urlencode($title);
					array_push($inbox->items, $item);
					fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename($a) . '/inbox.json', 'w+'), json_encode($inbox));
				}
				$m = json_decode(file_get_contents(__DIR__ . '/msg.json'));
				$m->$title = new Message($_POST['subject'], $rec, getname(), $_POST['body']);
				fwrite(fopen(__DIR__ . '/msg.json', 'w+'), json_encode($m));
				exit("Your message has been sent. <a href=\"messages\">View all private messages</a>");
			} else {
				return;
			}
		}
		send();
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table width="100%">
			<tbody>
				<tr>
					<td><label for="to">Recipients (newline between each username):</label></td>
					<td><textarea oninput="if (this.value!=''){this.rows = this.value.split('\n').length;}else{this.rows='3';}" rows="3" cols="30" id="to" name="to" placeholder="admin&#10;user1&#10;anotheruser"><?php if (isset($_POST['subject'])) echo htmlspecialchars($_POST['to']); ?></textarea></td>
				</tr>
				<tr>
					<td><label for="subject">Message subject:</label></td>
					<td><input type="text" id="subject" name="subject" placeholder="Review for Friday's test" value="<?php if (isset($_POST['subject'])) echo htmlspecialchars($_POST['subject']); ?>" /></td>
				</tr>
				<tr>
					<td><label for="body">Message body (PLAIN TEXT ONLY):</label></td>
					<td><textarea placeholder="Review the following stuff:&#10; - Vocab&#10; - Grammar&#10; - Literature" rows="10" style="width:100%;" id="body" name="body"><?php if (isset($_POST['body'])) { echo htmlspecialchars($_POST['body']); } ?></textarea></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="Confirm and Send" />
	</form>