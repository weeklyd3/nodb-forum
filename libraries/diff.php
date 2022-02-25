<?php
/* A very ambitious attempt at a diff program
 in PHP. LICENSE file still applies. */
function diff(string $old, string $new, string $oldcaption = "Old revision", string $newcaption = "New revision") {
    if ($old === $new) { ?>Nothing was changed.<?php return;}
    foreach (array(&$old, &$new) as &$string) {
        $string = explode("\n", $string);
    }
	$lines = max(count($old), count($new));
	$unifiedVersion = array();
	for ($i = 0; $i < $lines; $i++) {
		$unifiedEntry = array();
		if (isset($old[$i])) {
			$unifiedEntry['old'] = $old[$i];
		}
		if (isset($new[$i])) {
			$unifiedEntry['new'] = $new[$i];
		}
		array_push($unifiedVersion, $unifiedEntry);
	}
	?><table class="fixed-layout table exempt-from-format" style="width: 100%;">
		<tr>
		<th style="width: 50%;"><?php echo htmlspecialchars($oldcaption); ?></th>
		<th style="width: 50%;"><?php echo htmlspecialchars($newcaption); ?></th>
		</tr>
		<?php
		$rowsSkipped = 0;

		foreach ($unifiedVersion as $line) {
			$oldLine = isset($line['old']) ? $line['old'] : null;
			$newLine = isset($line['new']) ? $line['new'] : null;
			if ($oldLine === $newLine) {
				$rowsSkipped++;
				continue;
			}
			if ($rowsSkipped !== 0) {
				?><tr>
					<td colspan="2">
						(<?php echo $rowsSkipped; ?> identical row(s) skipped)
					</td>
				</tr><?php
			}
			$rowsSkipped = 0;
			$inlineDiff = inlineDiff($oldLine, $newLine);
			?><tr><td><?php echo $inlineDiff['old']; ?></td><td><?php echo $inlineDiff['new']; ?></td></tr><?php
		}
	if ($rowsSkipped > 0) {
		?><tr><td colspan="2">(<?php echo $rowsSkipped; ?> identical row(s) skipped)</td></tr><?php
	} ?>
	</table><?php
}
function inlineDiff(?string $oldline, ?string $newline): array {
	$inlineDiff = array('old' => '', 'new' => '');
	if (!isset($oldline) || !isset($newline)) {
		if (!isset($oldline)) {
			$inlineDiff['old'] = '<i>(empty)</i>';
		}
		if (!isset($newline)) {
			$inlineDiff['new'] = '<i>(empty)</i>';
		}
	}
	if ($oldline === "") {
		$inlineDiff['old'] = '<i>(blank)</i>';
	}
	if ($newline === "") {
		$inlineDiff['new'] = '<i>(blank)</i>';
	}
	$old = str_split($oldline);
	$new = str_split($newline);
	$un = array();
	$max = max(count($old), count($new));
	for ($j = 0; $j < $max; $j++) {
		$oldChar = isset($old[$j]) ? $old[$j] : null;
		$newChar = isset($new[$j]) ? $new[$j] : null;
		$oldTagOpened = false;
		$newTagOpened = false;
		if (isset($oldChar) && isset($newChar)) {
			if ($oldChar === $newChar) {
				$inlineDiff['old'] .= htmlspecialchars($oldChar);
				$inlineDiff['new'] .= htmlspecialchars($newChar);
			} else {
				$oldTagOpened = true;
				$newTagOpened = true;
				$inlineDiff['old'] .= '<span style="background-color: red; color: white;">' . htmlspecialchars($oldChar);
				$inlineDiff['new'] .= '<span style="background-color: lime; color: black;">' . htmlspecialchars($newChar);
			}
		}
		if (isset($oldChar) && !isset($newChar)) {
			$oldTagOpened = true;
			$inlineDiff['old'] .= '<span style="background-color: red; color: white;">' . htmlspecialchars($oldChar);
		}
		if (!isset($oldChar) && isset($newChar)) {
			$newTagOpened = true;
			$inlineDiff['new'] .= '<span style="background-color: lime; color: black;">' . htmlspecialchars($newChar);
		}
		if ($oldTagOpened) {
			$inlineDiff['old'] .= '</span>';
		}
		if ($newTagOpened) {
			$inlineDiff['new'] .= '</span>';
		}
	}
	return $inlineDiff;
}
