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
	<?php 
		if (isset($_GET['user'])) 
			$username = $_GET['user'];
		else 
			header('location: ../users.php');
	?>
	<base href="../" />
    <title>User <?php echo htmlspecialchars($username); ?></title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
  <?php
  if (file_exists('../data/accounts/'.cleanFilename($username).'/user.txt')) {
	  $json = json_decode(file_get_contents('../data/accounts/'.cleanFilename($username).'/user.json'));
	  ?>
	  <h2>Profile: <?php echo htmlspecialchars($username); ?></h2>
	  <?php 
	  if (file_exists('../data/accounts/'.cleanFilename($username).'/ban.txt')) {
		  ?><div style="background-color:red;">This user account has been blocked and is unable to respond to messages. Block reason: <i><?php echo file_get_contents('../data/accounts/'.cleanFilename($username).'/ban.txt'); ?></i></div><?php
	  } ?>
	  <table class="table" width="100%"><tr>
	  <td rowspan="3" valign="top"><h3>About Me</h3>
	  <?php 
	  if ($json->text) {
		$parsedown = new Parsedown;
		echo $parsedown->text($json->text);
	   } else {
		   ?><span style="color:#eeeeee;">This user has not filled it in. Time to get a detective to find out their profile!</span><?php
	   } ?>
	  </td><td title="Personal Web Site" style="width:0px;"><?php 
	  	if (filter_var($json->site, FILTER_VALIDATE_URL)) {
			  ?>
			  <a href="<?php echo htmlspecialchars($json->site); ?>"><?php 
			  if (strlen($json->site) < 31) {
				  echo htmlspecialchars($json->site); 
			  } else {
				  $site = $json->site;
				  echo htmlspecialchars(substr($site, 0, 30) . '...');
			  }?></a>
			  <?php
		  } else {
			  echo 'None';
		  }
	  ?></td>
	  <tr><td style="width:0px;" title="GitHub link">
		<a href=<?php 
			if ($json->github) {
				?>"https://github.com/<?php echo htmlspecialchars($json->github); ?>"<?php
			} else {
				echo 'javascript:;';
			}
		?>><?php 
		if ($json->github) {
		echo htmlspecialchars($json->github);
		 } else {
			 echo 'No GitHub link specified';
		 } ?></a>
	  </td></tr><tr>
	  <td>Last active: <?php echo file_exists('../data/accounts/'.cleanFilename($username).'/lastactive.txt') ? dateDiff((int) file_get_contents('../data/accounts/'.cleanFilename($username).'/lastactive.txt'), time()) : "Date unknown"; ?></td>
	  </tr></table>
	  <h3>User Contributions</h3>
	  <p>Below you can find content that this user has created.</p>
	  <?php 
	  $polls = json_decode(file_get_contents('../polls/polls.json'));
	  $polls = (array) (isset($polls->$username) ? $polls->$username : array());
	  	  $esuser = htmlspecialchars(urlencode($username));

	  ?>
	  <div>
		  <h3>User subpages</h3>
		  <a href="account/pages/index.php?username=<?php echo $esuser; ?>">View subpages</a>
	  </div>
	  <div>
		  <h3><?php echo count($polls); ?> poll(s)</h3>
		  <ul>
			  <?php if (count($polls) === 0) {
		  	?><li>(this user has no polls)</li><?php
			  } 
	  $Parsedown = new Parsedown;
		  foreach ($polls as $id => $poll) {
			  $espoll = htmlspecialchars(urlencode($id));
			  ?><li>
				  <a href="polls/poll.php?user=<?php echo $esuser; ?>&id=<?php echo $espoll; ?>"><h4><?php echo htmlspecialchars($poll->title); ?></h4></a>
				  <p><?php echo substr(strip_tags($Parsedown->text($poll->description)), 0, 30); 
			  if (strlen(strip_tags($Parsedown->text($poll->description))) > 30) {
				  ?>...<?php
			  }
			  ?></p>
			  </li><?php
		  }
		  ?>
		  </ul>
	  </div>
	  <div>
		  <h3>Topics and Posts</h3>
		  <ul><?php 
	  	$contribsPath = __DIR__ . '/../data/accounts/' . cleanFilename($_GET['user']) . '/contribs.json';
	if (!file_exists($contribsPath)) $contribs = array();
	else $contribs = json_decode(file_get_contents($contribsPath));

	if (count($contribs) === 0) {
		?><li>No topics or posts.</li><?php
	}
		foreach ($contribs as $contrib) {
			if (!isset($contrib->post)) {
				$config = json_decode(file_get_contents('../data/messages/' . cleanFilename($contrib->room) . '/config.json'));
				$author = $config->author;
				if (file_exists('../data/messages/' . cleanFilename($contrib->room) . '/del.json')) {
					if ($author !== getname() && !verifyAdmin()) continue;
				}
			} else {
				$messages = json_decode(file_get_contents('../data/messages/' . cleanFilename($contrib->room) . '/msg.json'));
				$message = $messages->{$contrib->post};
				if (isset($message->del)) {
					if ($message->author !== getname() && !verifyAdmin()) continue;
				}
			}
			?><li><h4><a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($contrib->room));
			if (isset($contrib->post)) {
				?>#topic-message-<?php
				echo htmlspecialchars(urlencode($contrib->post));
			}
			?>"><?php echo htmlspecialchars($contrib->room); ?></a></h4> (<?php echo isset($contrib->post) ? "Reply" : "Topic"; ?>)</li><?php
		}
	  ?></ul>
	  </div>
	  <?php
  } else {
	  echo 'Bad user name';
  }