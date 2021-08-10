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
	<base href="../" />
    <title>Password Reset</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<div class="blanket" style="display: block;">
	<div class="overlay" style="display: block;">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Reset password</h2>
	<?php 
	if (!isset($_POST['page'])) {
		?>
		Welcome to the Password Reset wizard! To continue, you will need:
		<ul>
			<li>Your security question answer</li>
			<li>Your username</li>
		</ul>
		<input type="hidden" name="page" value="user" />
		<input type="submit" value="OK" />
		<?php
	}
	if (isset($_POST['page'])) {
		if ($_POST['page'] == 'user') {
			?>
			<label>Enter your username:
			<input type="text" name="user" /></label>
			<input type="submit" value="Next" />
			<input type="hidden" name="page" value="answer" />
			<?php
		}
		if ($_POST['page'] == 'answer') {
			if (!file_exists('../data/accounts/'.cleanFilename($_POST['user']).'/question.json')) {
				echo 'Invalid username';
			} else {
				?>
				That user exists, and has a security question of:
				<pre><?php 
					$json = file_get_contents('../data/accounts/'.cleanFilename($_POST['user']).'/question.json');
					$question = json_decode($json);
					echo $question->question;
				?></pre>
				<label>
				Answer:
				<input type="text" name="answer" />
				</label>
				<input type="submit" name="page" value="verify" />
				<input type="hidden" name="user" value="<?php echo htmlspecialchars($_POST['user']); ?>" />
				<?php
			}
		}
		if ($_POST['page'] == 'verify') {
			?>
			Verifying password...<br />
			<?php
			$answer = file_get_contents('../data/accounts/'.cleanFilename($_POST['user']).'/question.json');
			$object = json_decode($answer);
			if (password_verify($_POST['answer'], $object->answer)) {
				?>
				Answer is correct
				<input type="hidden" name="answer" value="<?php echo htmlspecialchars($_POST['answer']); ?>" />
				<input type="hidden" name="page" value="reset" />
				<hr />
				<label>Type a new password. You better not forget it! <br />
				<input type="password" name="new" required="required" /></label>
				<input type="hidden" name="user" value="<?php echo htmlspecialchars($_POST['user']); ?>" />
				<input type="submit" value="reset" />
				<input type="hidden" name="answer" value="<?php echo htmlspecialchars($_POST['answer']); ?>" />
				<?php
			} else {
				?>
				Your answer is incorrect. Please press the Back button and re-enter it.

				<?php
			}
		}
		if ($_POST['page'] == 'reset') {
			?>
			<ol>
			<li>Verifying...</li>
			<?php
			$answer = file_get_contents('../data/accounts/'.cleanFilename($_POST['user']).'/question.json');
			$object = json_decode($answer);
			if (password_verify($_POST['answer'], $object->answer)) { 
				?>
				<li>Changing password...</li>
				<?php
				$handle = fopen('../data/accounts/'.cleanFilename($_POST['user']).'/psw.txt', 'w+');
				$hash = password_hash($_POST['new'], PASSWORD_DEFAULT);
				if ($hash) {
					$written = fwrite($handle, $hash);
					if ($written) {
						?>
						<li>Password reset. Log in using the new password with username <code><?php echo htmlspecialchars($_POST['user']); ?></code>.</li>
						<?php
					} else {
						?> 
						<li>Could not change password</li>
						<?php
					}
				} else {
					?>
					<li>Could not generate hash</li>
					<?php
				}
			} else {
				?>
				<li>Bad request, may be forged</li>
				<?php
			}
			?>
			</ol>
			<?php 
		}
	}
	?>
	</form>
	</div>
	</div>