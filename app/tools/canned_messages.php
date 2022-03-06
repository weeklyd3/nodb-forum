<?php 
require 'header.php';
?>
<h2>Message Templates</h2>
<p>Use this form to get a message template to send to a user.</p>
<p>Click the text <span style="background-color:skyblue;color:black;">with a light blue background color</span> to customize your message.</p>
<p>Please note that misuse of this tool will likely get your administrator privileges revoked. There may or may not be a user to test these messages on.</p>
<form action="app/tools/sendcannedmessage.php" method="post">
<h3>Report Handled</h3>

<p>Hi from the moderation team,<br /><br />Your report on <label for="1-1" class="none">Report identifier: </label><input type="text" name="report-identifier" id="1-1" placeholder="Text to identify report" /> has been handled. I have <label for="1-2" class="none">Report status:</label><input id="1-2" name="report-status" type="text" placeholder="Result of report (declined, acted on, etc.)" size="40" /> it because <label for="1-3" class="none">Handle reason:</label><input id="1-3" name="report-resolve-reason" placeholder="Reason why you acted this way" size="30" />. Thanks for helping keep our site clean!</p>
<input type="submit" name="send-report-handled" value="Send" />
</form>
<form action="app/tools/sendcannedmessage.php" method="post">
	<h3>Topic deleted</h3>
<p>Hi from the moderation team,<br /><br />Your topic <label for="2-1" class="none">Deleted topic:</label><input type="text" id="2-1" name="deleted-topic-identifier" placeholder="Text to identify deleted topic" size="25" /> has been deleted. Having something deleted can be frustrating, so we encourage you to ask any questions you have about the deletion. The deletion reason given was: <label for="2-2" class="none">Delete reason:</label><input id="2-2" name="deleted-topic-reason" placeholder="Deletion reason" />. You are encouraged to edit it and flag it to request undeletion. Thank you for your cooperation!</p>
<input type="submit" name="send-deleted-topic" value="Send" />
</form>
<form action="app/tools/sendcannedmessage.php" method="post">
	<h3>Rule change</h3>
<p>Hi from the moderation team,<br /><br />We have updated the rules for our site. The new rules will be in effect <label class="none" for="3-1">Date where rules will be in effect:</label><input id="3-1" type="text" name="rule-change-date" placeholder="Date when new policy comes into effect" size="37" />. You are encouraged to re-read the policy pages to make sure you understand the rules. More information is available at <label class="none" for="3-2">Article URL:</label><input type="text" id="3-2" name="rule-change-url" placeholder="URL for more info" />. By using the site after the date posted above, you consent to the new policies. Thank you for being a member of this site!</p>
	<p class="smaller">You can create an <a href="articles/">article</a> and set a <a href="app/tools/banner.php">banner</a> to inform others about the change. Creating an article will also generate a link to share above.</p>
	<input type="submit" name="send-rule-change" value="Send" />
</form>
<style>
	.none {
		display: none;
	}
	body > form > p > input {
		border-radius: 2px !important;
		border: none !important;
		padding: 0 !important;
		background-color: skyblue !important;
		color: black;
		outline: none !important;
	}
</style>