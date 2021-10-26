<?php 
function generateOptions(array $arr) {
	$options = array();
	foreach ($arr as $key => $value) {
		$name = (int) (explode('_', $key)[0]) - 1;
		if (!isset($options[$name])) {
			$options[$name] = array();
		}
		if (!startsWithNumber($key)) continue;
		if (endsWith($key, "-required")) {
			$options[$name]['required'] = true;
		} else if (endsWith($key, '-description')) {
			$options[$name]['description'] = $value;
		} else if (endsWith($key, "-options")) {
			$value = str_replace(array("\r\n", "\r"), "\n", $value);
			$options[$name]['options'] = array_unique(array_filter(explode("\n", $value), function($a) { return $a !== ''; }));
		} else if (endsWith($key, '-type')) {
			if (in_array($value,
			array('dropdown', 'text', 'radio', 'number', 'e-mail', 'textarea'))) {
				$options[$name]['type'] = $value;
			} else {
				$options[$name]['type'] = 'text';
			}
		}
	}
	foreach ($options as $key => $filter) {
		if (count($filter) === 0) {
			unset($options[$key]);
			continue;
		}
		if (!isset($options[$key]['required'])) {
			$options[$key]['required'] = false;
		}
	}
	return array_values($options);
}
function viewForm(array $options, bool $print = false): void {
	$guidelines = array(
		'dropdown' => "Please select your option",
		'text' => "Please enter your response",
		"radio" => "Please fill in the bubble of your answer.",
		"number" => "Please enter a number",
		"e-mail" => "Please enter an e-mail address.",
		"textarea" => "Please write your response."
	);
	require_once '../libraries/parsedown.php';
	?><ol><?php
	foreach ($options as $number => $question) {
		?><li><h3>Question <?php echo $number + 1; ?></h3><?php
		$Parsedown = new Parsedown;
		echo $Parsedown->text($question['description']);
		?><hr /><?php
		if ($print) { echo $guidelines[$question['type']]; }
		if ($question['type'] == 'dropdown') {
			?><label>Your answer: <select<?php if ($print) { ?> size="10"<?php } ?> name="question-<?php echo $number; ?>"<?php
			if ($question['required']) {
				?> required="required"<?php
			}
			?>><?php if (!$print) { ?><option value="" selected="selected" style="color:gray;">Choose...</option><?php }
			foreach ($question['options'] as $option) {
				?><option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option><?php
			}
			?></select></label><?php
		}
		if ($question['type'] == 'text') {
			?><label>Your answer: <input type="text" name="question-<?php echo $number; ?>" <?php if ($question['required']) { ?>required="required" <?php } ?>/></label><?php
		}
		if ($question['type'] == 'radio') {
			?><fieldset><legend>Your answer is...</legend>
			<?php 
			foreach ($question['options'] as $option) {
				?><label><input type="radio" value="<?php echo htmlspecialchars($option); ?>" name="question-<?php echo $number; ?>" <?php if ($question['required']) { ?>required="required" <?php } ?>/> <?php echo htmlspecialchars($option); ?></label> <?php
			}
			?></legend>
			</fieldset><?php
		}
		if ($question['type'] == 'number') {
			?><label>Your answer: <input type="number" name="question-<?php echo $number; ?>" <?php if ($question['required']) { ?>required="required" <?php } ?>/><?php
		}
		if ($question['type'] == 'e-mail') {
			?><label>Your answer: <input type="email" name="question-<?php echo $number; ?>" <?php if ($question['required']) { ?>required="required" <?php } ?>/><?php
		}
		if ($question['type'] == 'textarea') {
			?><label>Your answer:<br /><textarea rows="5" name="question-<?php echo $number; ?>" cols="50"<?php if ($question['required']) { ?> required="required"<?php } ?>></textarea><?php
		}
		?></li><?php
	}
	?></ol><?php
}