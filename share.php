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
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
    <title>Share a Topic</title>
  </head>
  <body>
  <?php if (!isset($_GET['room'])) die("Specify a room"); 
  if (!file_exists("data/messages/".cleanFilename($_GET['room'])."/msg.json")) die("Bad room title");
  $json = json_decode(file_get_contents("data/messages/".cleanFilename($_GET['room']).'/config.json'));
  $title = $json->title;
  ?>
  <h2>Share</h2>
  <ul>
  <li>E-mail: 
  <a href="mailto:?body=Here's a topic you might be interested in:%0D%0Ahttp://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/webchat.php?title=<?php echo htmlspecialchars($title); ?>%0D%0AIf you want to discuss, you may log in to your account and send a message.%0D%0A%0D%0AHave fun!">E-mail this to a friend</a></li>
  <li>Share a link:<br />
  <label>link: <input type="text" oninput="this.value=this.getAttribute('value');" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webchat.php?room=<?php echo htmlspecialchars(urlencode($title)); ?>" /></label></li>
  </ul>
  <?php include_once('./public/footer.php'); ?>