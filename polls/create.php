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
    <title>Compose Poll</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
	if (!isset($_COOKIE['login'])) die("<h2>You are not logged in.</h2><p>Log in to create polls.</p>");
	require '../libraries/formfuncs.php';
	if (isset($_POST['preview'])) {
		?><h2>Your response will not be saved!</h2><p>Below is a preview.</p><?php
		viewForm(generateOptions($_POST));
	}
	if (isset($_POST['save'])) {
		$json_contents = generateOptions($_POST);
		if (!file_exists(__DIR__ . '/polls.json')) {
			fwrite(fopen(__DIR__ . '/polls.json', 'w+'), "{}");
		}
		$json = json_decode(file_get_contents(__DIR__ . '/polls.json'));
		$name = getname();
		$title = $name . "|" . time();
		if (!isset($json->$name)) $json->$name = new stdClass;
		$json->$name->$title = new stdClass;
		$json->$name->$title->title = $_POST['title'];
		$json->$name->$title->description = $_POST['description'];
		$json->$name->$title->questions = new stdClass;
		$json->$name->$title->questions = $json_contents;
		fwrite(fopen(__DIR__ . '/polls.json', 'w+'), json_encode($json));
		?><h2>Link:</h2><p>Copy the link address below.</p><p><a href="polls/viewpoll.php?user=<?php echo htmlspecialchars(urlencode(getname())); ?>&id=<?php echo htmlspecialchars(urlencode($title)); ?>">Link here</a></p><?php
	}
	?>

	<h2>Question Editor</h2>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div style="background-color:gold;">
	<button type="submit" name="create-poll" style="background-color:transparent;border:none;padding:0;" title="Add question"><img src="img/icons/plusicon.png" alt="Add question" /> Add question</button>
	<button type="submit" style="background-color:transparent;border:none;padding:0;" name="save" title="Save"><img src="img/icons/validateform.png" alt="Save" /> Save</button>

	<button type="submit" style="background-color:transparent;border:none;padding:0;" title="Get help" name="get-help"><img src="img/icons/helpicon.png" alt="Get help" /></button>
	<button type="submit" style="background-color:transparent;border:none;padding:0;" title="Preview" name="preview"><img src="img/icons/preview-icon.png" alt="Preview" /> Preview form</button>
	</div>
	<h3>Details</h3>
	<ul>
		<li><label>Form title: <input type="text" name="title" value="<?php if (isset($_POST['title'])) { echo htmlspecialchars($_POST['title']); } ?>" /></label></li>
		<li><label>Form description: <textarea name="description"><?php if (isset($_POST['description'])) { echo htmlspecialchars($_POST['description']); } ?></textarea></label></li>

	</ul>
	<?php
	$a = generateOptions($_POST);
	$i = array();
	$c = count($a);
	if (isset($_POST['get-help'])) {
		?><h3>Help</h3>
		<p>You can use this tool to make polls for users to take.</p>
		<p>The question editor consists of four fields:</p>
		<ul>
			<li>Type your question description in the <strong>description</strong> field. This will be rendered as Markdown, so syntax like **<strong>bold</strong>** and *<em>italic</em>* is supported. This can be of unlimited length.
				<blockquote>NOTE:<br />This will not be rendered as a <?php $Parsedown = new Parsedown; echo $Parsedown->line("`<label>`"); ?> element, so you can put directions here.</blockquote>
			</li>
			<li>
			The checkbox indicates if the field has to be filled in before the form can be submitted. <ul><li><strong>Check</strong> it if it collects required information, such as a respondent's name.</li><li><strong>Leave this field unchecked</strong> for fields that you want to collect information, but is not strictly required for the form.</li></ul>
			</li>
			<li>
			The "type" field indicates the type of input that can be submitted. The types are explained below:
				<table class="table">
					<tr>
						<th>Name</th>
						<th>HTML element</th>
						<th>Description</th>
						<th>Demo</th>
					</tr>
					<tr>
						<td>Drop down menu</td>
						<td><code>&lt;select></code></td>
						<td>When unfocused, displays selected option.<br /> When focused, displays list of options.</td>
						<td><label>Choose your fruit: 
						<select>
							<option>Apple</option>
							<option>Strawberry</option>
							<option>Orange</option>
							<option>Pear</option>
						</select></label></td>
					</tr>
					<tr>
						<td>Single line text</td>
						<td><code>&lt;input type="text" /></code></td>
						<td>Input for one line only. Strips new lines on paste.</td>
						<td><label>Enter something: <input type="text" /></label></td>
					</tr>
					<tr>
						<td>Radio buttons</td>
						<td><code>&lt;input type="radio" /></code></td>
						<td>A group of choices where only one choice can be selected.</td>
						<td>
							<fieldset><legend>Choose option</legend>
							<label><input name="demo" type="radio" /> One</label>
							<label><input name="demo" type="radio" /> Two</label>
							<label><input name="demo" type="radio" /> Three </label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td>Number input</td>
						<td><code>&lt;input type="number" /></code></td>
						<td>Restricts to numbers.</td>
						<td><label>Your test score: <input type="number" /></label></td>
					</tr>
					<tr>
						<td>E-mail address input</td>
						<td><code>&lt;input type="email" /></code></td>
						<td>Requires a valid e-mail address before submitting.<br />Try entering an invalid e-mail address, and then pressing Enter:</td>
						<td><label>Your e-mail: <input type="email" /></label></td>
					</tr>
					<tr>
						<td>Multi-line input</td>
						<td><code>&lt;textarea></code></td>
						<td>Multi line plain text input.</td>
						<td><label>Enter your essay question: <br /><textarea rows="5" cols="40"></textarea></label></td>
					</tr>
				</table>
			</li>
			<li>If you selected "radio buttons" or "dropdown" as the type, enter your options, separated by newlines, in the <strong>options</strong> field.</li>
		</ul><?php
	}
	if ($c === 0) {
		?><p>Press "+" above to add a question.</p><?php
	}
	if (isset($_POST['create-poll']))
		$a[$c] = array(
			"description" => "Enter description here",
			"type" => "text",
			"options" => array("one", "two", "three"),
			"required" => false,
		);
	$types = array('dropdown' => "Drop down menu", 'text' => "Single line text", 'radio' => "Radio buttons", 'number' => "Number input", 'e-mail' => "E-mail address input", "textarea" => "Multi line input");
	?><ol><?php
	foreach ($a as $option) {
		array_push($i, '');
		?><li><h3>Edit question <?php echo count($i); ?></h3>
		<ul>
		<li><label>Description: <br /><textarea name="<?php echo count($i); ?>_question-description" cols="50" rows="5"><?php echo htmlspecialchars($option['description']); ?></textarea></label></li><li><label><input type="checkbox" name="<?php echo count($i); ?>_question-required" value="a"<?php if ($option['required']) { ?> checked="checked"<?php } ?> /> Mark as required</li>
		<li><label>Type: <select name="<?php echo count($i); ?>_question-type">
		<?php 
		foreach ($types as $type => $label) {
			?><option <?php 
			if ($option['type'] == $type) { ?>selected="selected" <?php } ?>value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($label); ?></option><?php
		} ?>
		</select></li>
		<li><label>If you selected "Radio buttons" or "Dropdown", enter options:<br />
		<textarea rows="5" cols="50" name="<?php echo count($i); ?>_question-options"><?php echo htmlspecialchars(implode("\n", $option['options'])); ?></textarea></label>
		</ul>
		</li><?php
	}
	?></ol>