<html>
  <head>
	<base href="../" />
    <title>Forums &mdash; Installation</title>
	<?php
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
		if (file_exists('../config.json')) {
			header('Location: ..');
		}
		$title = $_POST['title'];
		class Installation {
			public $forumtitle = "default";
		}
		$install = new Installation;
		$install->forumtitle = $title;
		$json = json_encode($install);
		$handle = fopen("../config.json", 'w+');
		$status = fwrite($handle, $json);
		fclose($handle);
		if ($status) {
			echo "> Installed!";
		}
	?>
	<h1>Welcome!</h1>