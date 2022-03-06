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
?><html>
  <head>
    <title>Data Collection Policy</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <p style="background-color:red;">This is an official policy. Updated 6/17/2021.</p>
  <table><tr><td>
		<h2 id="accept">Acceptance of this policy</h2>
		<p>By signing up, you agree to these terms. If you do not agree to these terms, then do not sign up. This document is not the only document people accept to sign up, the Terms of Use is also one of them.</p>
		<h2 id="top">Data Collection</h2>
		<p>This document outlines the data this forum collects and what the software does with it.</p>
		<p><strong>This site does not give away user data or run web analytic software. Your data is only kept on this server and is never shared to third-party vendors.</strong></p>
		<h3 id="dataCollected">Data Collected</h3>
		<ul>
		<li>Your username. This is asked for from you when you sign up. The username is available publicly and is available to anyone who views forums in which you post messages.</li>
		<li>Your messages. This is collected when you post on the forums. These messages are posted at your consent, and you can avoid forum participation if you desire.</li>
		</ul>
		<h3 id="usage">Data Usage</h3>
		<ul>
		<li>To "sign" your posts using your digital identity. You can choose any user name you want, thus making a desirable signature on the posts you create. This is all up to you, and you can stay completely anonymous based on your name.</li>
		<li>To ensure the protection of your accounts. <strong>Usernames are stored as plain text, and are viewable by <em>anyone.</em></strong> Passwords, on the contary, are hashed, making it impossible for anyone to read. When you log in, the entered password is hashed, and the two hashes are compared.</li>
		</ul>
		<h3 id="options">Your Data Options</h3>
		<ul>
		<li>You can stay anonymous by choosing a pseudonym as your username. As long as it is not offensive or an impersonation, you can sign up with it to achieve privacy online. You do not have to use your real name as the username, in fact, it is discouraged.</li>
		<!--<strong><li>You have the option to delete your data at any time. You can request account deletion using the contact form at the bottom of every form, then tell the support team that you want to delete your account. When this happens, all of your account information is promptly, permanently deleted from the server, and contributions are anonymized as deleted-user-XXXXXXXXXX where XXXXXXXXXX is a number.</li></strong>-->
		</ul>
	</td>
	<td style="vertical-align:top;" width="200">
	<div style="position:sticky; top:4em; z-index:0;">
	<h2>Contents</h2>
	<a href="#accept">Acceptance</a>
	<br>
	<a href="#top">Data collection policy</a>
	<br>
	&nbsp;&nbsp;<a href="#dataCollected">Data collected</a>
	<br>
	&nbsp;&nbsp;<a href="#usage">Data Usage</a>
	<br>
	&nbsp;&nbsp;<a href="#options">Data Options</a></div>
	</td>
	</tr>
	</table>
	<?php
	include('./public/footer.php');
	?>
  </body>
</html>