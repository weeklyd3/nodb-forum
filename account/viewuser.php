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
    <title>Forums &mdash; User <?php echo htmlspecialchars($username); ?></title>
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
	  <table class="table" width="100%"><tr>
	  <td rowspan="2" valign="top"><h3>About Me</h3>
	  <?php 
	  if ($json->text) {
		$parsedown = new Parsedown;
		echo $parsedown->text($json->text);
	   } else {
		   ?><span style="color:#eeeeee;">This user has not filled it in. Time to get a detective to find out their profile!</span><?php
	   } ?>
	  </td><td title="Personal Web Site" style="max-width:50%;"><?php 
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
	  <tr><td width="300" style="max-width:50%;" title="GitHub link">
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
	  </td></tr></table>
	  <script>hljs.highlightAll();</script>
	  <?php
  } else {
	  echo 'Bad user name';
  }
  include('../public/footer.php');