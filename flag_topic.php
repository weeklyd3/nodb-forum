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
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
    <title>Flag a Topic</title>
  </head>
  <body>
	<?php if (!isset($_GET['room'])) die("no room specified"); 
	if (!getname()) die("log in to flag");
	if (!file_exists(__DIR__ . '/data/messages/'.cleanFilename($_GET['room']).'/config.json')) die("Bad title");
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($_GET['room']) . '/config.json'));
	$name = getname();
	class Flagged extends emptyClass {
		public $reason = '';
	}
	if (isset($_POST['flag'])) {
		?><p>You have raised flag: <strong><?php echo htmlspecialchars($_POST['flag']); ?></strong></p><?php
		$config->flags->$name = new Flagged;
		$config->flags->$name->reason = $_POST['flag'];
		if (isset($_POST['input'])) $config->flags->$name->modText = $_POST['input'];
		fwrite(fopen(__DIR__ . '/data/messages/' . cleanFilename($_GET['room']) . '/config.json', 'w+'), json_encode($config));
	}
	?>
	<h2>Why is this topic inappropriate?</h2>
	<?php if (isset($config->flags->$name)) { ?><p>You have already raised a flag.</p><?php } ?>
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<fieldset<?php if (isset($config->flags->$name)) echo ' disabled="disabled"'; ?>><legend>Flag reasons</legend>
		<ul style="list-style:none;"><li>
		<label><input required="required" type="radio" name="flag" value="Spam" /><strong>Spam</strong>: A post that only serves to promote a service or product without disclosing the author's affiliation with that service or product.</label> (<a href="img/spam.png">Example</a>)</li>
		<li><label><input required="required" type="radio" name="flag" value="Gibberish" /><strong>Gibberish:</strong> Posts created with only "filler text", like "asdfdasfsda". </label></li>
		<li><label><input required="required" type="radio" name="flag" value="Offensive" /><strong>Rude:</strong> Offensive content.</label></li>
		<li><label><input required="required" type="radio" name="flag" value="Pirated" /><strong>Pirated software:</strong> Not entirely spam, but is illegal. </label></li>
		<li>
			<details>
				<summary>Not a good fit for this site</summary>
				<ul style="list-style:none;">
				<li><label><input required="required" type="radio" name="flag" value="Vague" /><strong>Too vague:</strong> This topic is too vague to be useful for future visitors. </label></li>
				<li><label><input required="required" type="radio" name="flag" value="More Focus" /><strong>Unfocused:</strong> The topic asks too many questions.</label></li>
				<li>
					<details>
						<summary>Off topic</summary>
						<ul style="list-style:none;">
						<li><label><input required="required" type="radio" name="flag" value="Not able to be discussed" /><strong>Cannot be discussed:</strong> This topic cannot be discussed because it is not seeking discussion.</label></li>
						<li><label><input required="required" type="radio" name="flag" value="Off topic" /><strong>Off-topic:</strong> This topic is not on-topic for this site.</label></li>
						</ul>
					</details>
				</li>
				</ul>
			</details>
		</li>
		<li><label><input required="required" type="radio" name="flag" value="Other" /><strong>Something else:</strong> Another issue which requires moderator attention.</label><br />
		<label>Why does this topic require moderator attention?<br />
		<textarea name="input" style="width:100%;" rows="5" placeholder="ONLY FILL THIS IN IF YOU ARE USING THE &quot;OTHER&quot; REASON. Otherwise, it will not be sent." disabled="disabled"></textarea></label>
		</li>
		</ul>
		<input type="submit" value="Flag!" /></fieldset>
	</form>
	<script>
	document.body.onclick = function() {
		if (document.querySelector('input[value=Other]').checked) { document.querySelector('textarea').required = 'required'; document.querySelector('textarea').disabled = ''; } else { document.querySelector('textarea').removeAttribute('required'); document.querySelector('textarea').disabled = 'disabled'; }
	}</script>
	</body></html>