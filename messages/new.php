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
		if (!isset($_COOKIE['login'])) die("Log in to write messages");
		function send() {
			if (isset($_POST['use-me']) || isset($_POST['cancel'])) {
				if (verifyAdmin()) {
					return;
				}
			}
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
				if (!file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json')) {
					fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json', 'w+'), "{}");
				}
				foreach ($rec as $n) {
					$m = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/msg.json'));
					$body = isset($_POST['wordwrap']) ? wordwrap($_POST['body'], isset($_POST['linelength']) ? (is_numeric($_POST['linelength']) ? $_POST['linelength'] : 75) : 75) : $_POST['body'];
					$m->$title = new Message($_POST['subject'], $rec, getname(), $body);
					fwrite(fopen(__DIR__ . '/../data/accounts/' . cleanFilename($n) . '/msg.json', 'w+'), json_encode($m));
				}
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
				</tr><?php 
				if (verifyAdmin()) {
					?><tr><td>You are an admin. Warn someone?</td><td><?php if (!isset($_POST['warning'])) { ?><fieldset><legend>Warning template:</legend>
					<ul style="list-style:none;padding:0px;">
						<li><label><input type="radio" name="warning" value="spam" /> Spammer</label></li>
						<li><label><input type="radio" name="warning" value="rude" /> Posting rude content</label></li>
						<li><label><input type="radio" name="warning" value="junk" /> Posting junk content</label></li>
						<li><label><input type="radio" name="warning" value="gibberish" /> Posting gibberish content</label></li>
					</ul></fieldset><?php } if (isset($_POST['warning'])) { ?><label hidden="hidden">DO NOT TOUCH this field.<textarea name="oldmsg"><?php echo htmlspecialchars($_POST['body']); ?></textarea></label>
					<?php } if (isset($_POST['warning'])) { ?><input type="submit" name="cancel" value="Close template" /> <?php } else { ?><input type="submit" name="use-me" value="Display template" /><?php } ?></td></tr><?php
					if (isset($_POST['warning'])) {
						?><tr><td>A warning has been set:</td><td><strong><?php echo htmlspecialchars($_POST['warning']); ?></strong></td></tr><?php
					}
				}
				?>
				<tr>
					<td><label for="wordwrap">Word wrap:</label></td>
					<td><input type="checkbox" id="wordwrap" name="wordwrap"<?php if (isset($_POST['wordwrap'])) { ?> checked="checked"<?php } ?> /> with <label>size <input type="number" value="<?php if (isset($_POST['linelength'])) { echo htmlspecialchars($_POST['linelength']); } ?>" name="linelength" placeholder="75" /></label></td>
				</tr>
				<tr>
					<td><label for="body">Message body (PLAIN TEXT ONLY):</label></td>
					<td><textarea placeholder="Review the following stuff:&#10; - Vocab&#10; - Grammar&#10; - Literature" rows="10" style="width:100%;" id="body" name="body"><?php 
					$useme = array(
						"spam" => "Hi,\n\nWe noticed that you were posting spam on our forum. It's okay to promote your product, as long as you:\n - don't talk about it too much\n - disclose how you are related\n   Note: Sometimes even a 'my' will be enough!\n\nSome of your posts may have been flagged by the community as spam and replied to with constructive criticism, please review them carefully. Thanks!\n\n-- moderation team",
						"rude" => "Hi,\n\nWe noticed that you were posting rude content on our forum. Some users may have responded to your posts with constructive criticism, please review your rude posts carefully. \n\nIn the future, please remember to be nice to other users. Instead of getting into a fight over something, try stepping away for a second and thinking about it. Thanks!\n\n-- moderation team",
						"junk" => "Hi,\n\nWe noticed you were posting some responses that did not add to the conversation. Some common examples of these are:\n - Bumping the question\n   i have the same problem!! can anyone halp? [sic.]\n - A new question\n   got it, thks! now I need to foo the bar. how? thks! [sic.]\n   A new question should be posted as a new topic.\n - A bumper's favorite:\n   BUMP! [sic.]\n   If a topic should be bumped to the front page, edit and improve it. This \n   will get more people interested.\n\nThese replies do not add anything to the topic, so they are discouraged. Please refrain from posting these kinds of replies in the future. Thanks!\n\n-- moderation team ",
						"gibberish" => "Hi,\n\nWe noticed that you were posting gibberish on our forum. Gibberish simply to bump a topic is discouraged. You can try researching the topic and writing a partial solution. Using replies as formatting sandboxes is also discouraged. Rather, ask for a formatting sandbox topic to be created, and reply with formatting tests there. Thanks!\n\n-- moderation team"
					);
					if (!isset($_POST['use-me'], $_POST['warning'])) {
						if (isset($_POST['oldmsg'])) {
							echo htmlspecialchars($_POST['oldmsg']);
						} else {
							if (isset($_POST['body'])) { 
								echo htmlspecialchars($_POST['body']); 
							}
						}
					} else {
						if (verifyAdmin()) {
							echo htmlspecialchars($useme[$_POST['warning']]);
						}
					} ?></textarea></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="Confirm and Send" />
	</form>