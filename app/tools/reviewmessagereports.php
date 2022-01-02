<?php 
require 'header.php';
?>
<h2>Review Message Reports</h2>
<?php 
$failmsg = "No reports to review. Review post flags?";
if (!file_exists('../../messages/reports.json')) {
	exit($failmsg);
}
$reports = json_decode(file_get_contents('../../messages/reports.json'));
	$reports->reports = array_filter($reports->reports, function($r) {
		return !isset($_POST[$r->id]);
	});
fwrite(fopen("../../messages/reports.json", "w+"), json_encode($reports));
if (count($reports->reports) === 0) {
	exit($failmsg);
}

?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<em>Note: It is recommended to start working from the top down, because the entries on the top are the oldest.</em>
		<input type="submit" value="Resolve selected reports" />
<table class="table">
	<tr>
		<td colspan="8" style="background-color:blue;text-align:center;">Oldest 50 reports</td>
	</tr>
	<tr>
		<th>Number</th>
		<th>From</th>
		<th>To</th>
		<th>Sending time</th>
		<th>Contents</th>
		<th># of times reported</th>
		<th>Report Reason(s)</th>
		<th>Mark as reviewed</th>
	</tr>
	<?php 
	$currentID = 0;
	$idsPrinted = array();
	foreach ($reports->reports as $report) {
		if ($currentID === 50) {
			break;
		}
		if (in_array($report->id, $idsPrinted)) {
			continue;
		}
		array_push($idsPrinted, $report->id);
			$currentID++;
		?><tr>
			<td><?php echo $currentID; ?></td>
			<td><?php echo htmlspecialchars($report->from); ?></td>
			<td><?php 
		$GLOBALS['report'] = $report;
		echo htmlspecialchars(implode(", ", $report->to)); ?></td>
			<td><?php echo friendlyDate($report->time); ?></td>
			<td><pre><code><?php echo htmlspecialchars($report->body); ?></code></pre></td>
			<td><?php 
		$postReports = array_filter($reports->reports, function($r) {
			return $r->id == ($GLOBALS['report'])->id;
		});
		echo count($postReports);
		?></td>
			<td><?php 
		foreach ($postReports as $reportedMsg) {
			?><pre><code><?php echo htmlspecialchars($reportedMsg->reason); ?></code></pre><?php
		}
		?></td>
			<td>
				<label>
					<span hidden="hidden">Mark: </span>
					<input type="checkbox" name="<?php echo htmlspecialchars($report->id); ?>" value="complete" /></label>
			</td>
		</tr><?php
	}
	?>
</table>