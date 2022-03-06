<?php 
include 'header.php';
?>
<h2>Send Confirmation</h2>
<p>Are you sure you want to send the message below? Please make changes accordingly.</p>
<form action="messages/new.php" method="post">
	<label>Canned message:<br /><<?php echo 'textarea'; ?> name="body" rows="5" cols="50">Hi from the moderation team,

<?php  
if (isset($_POST['send-report-handled'])) {
	?>Your report on <?php echo htmlspecialchars($_POST['report-identifier']); ?> has been handled. I have <?php echo htmlspecialchars($_POST['report-status']); ?> it because <?php echo htmlspecialchars($_POST['report-resolve-reason']); ?>. Thanks for helping keep our site clean!<?php
}
	if (isset($_POST['send-deleted-topic'])) {
		?>Your topic <?php echo htmlspecialchars($_POST['deleted-topic-identifier']); ?> has been deleted. Having something deleted can be frustrating, so we encourage you to ask any questions you have about the deletion. The deletion reason given was: <?php echo htmlspecialchars($_POST['deleted-topic-reason']); ?>. You are encouraged to edit it and flag it to request undeletion. Thank you for your cooperation!<?php
	}
		if (isset($_POST['send-rule-change'])) {
			?>We have updated the rules for our site. The new rules will be in effect <?php echo htmlspecialchars($_POST['rule-change-date']); ?>. You are encouraged to re-read the policy pages to make sure you understand the rules. More information is available at <?php echo htmlspecialchars($_POST['rule-change-url']); ?>. By using the site after the date posted above, you consent to the new policies. Thank you for being a member of this site!<?php
		}
?>


Thanks,
Moderation Team</textarea></label><br />
		<input type="hidden" name="subject" value="Message from moderation team" />
		<input type="hidden" name="wordwrap" value="" />
		<input type="submit" value="Next >" />
</form>
<p>After completing this step: Specify recipients then send.</p>
<p>Clicking <kbd>Next ></kbd> will take you to the new message page with the subject and body filled out.</p>