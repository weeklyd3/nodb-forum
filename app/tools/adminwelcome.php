<?php 
require "header.php";
?>
<h2>Welcome</h2>
<p>Welcome to the administrator dashboard! Here, you can help keep the site running by:</p>
<ul>
	<li>Reviewing flags, and taking appropriate action (<a href="app/tools/flags.php">Try here -- but don't resolve any without actually reviewing!</a>)</li>
	<li>Reminding users of behavioral problems by warning them via a private message (<a href="messages/new.php">Try here -- but don't falsely warn someone!</a>)</li>
	<li>Changing site settings</li>
	<li><a href="app/tools">Seeing statistics</a></li>
</ul>
<h2>Things to do &amp; Rules</h2>
<ol>
	<li><a href="account/changepass.php"><strong>Set a strong password NOW. Do not store it in your browser, but in a separate password manager.</strong></a> If you break the site (which can be done with a single click), you will be responsible.</li>
	<li><a href="account/signup.php?name=<?php echo htmlspecialchars(getname()) . "-ALT"; ?>">Do not use your administrator account on public computers or networks. Instead, sign up for an alternate account here.</a> Please note that the account is alternate on the new account's about me page.</li>
</ol>
<p>However, your main goal <strong>should still be to ask and answer topics.</strong> Becoming an administrator does not mean you can refrain from posting content. Being an administrator is also not just for fun, so you should provide reasons for every administrative action you perform.</p>