<?php 
include('header.php');
if (!isset($_GET['room']) || !isset($_GET['user'])) die("user or room not specified");
if (!isset($_GET['post'])) {
	chdir(__DIR__ . '/../../data/messages/' . $_GET['room']);
	?><h2>Remove flag from <?php echo htmlspecialchars($_GET['room']); ?></h2><?php
	if (!file_exists('config.json')) die("Bad title");
	$user = getname();
	$json = json_decode(file_get_contents('config.json'));
	if (!isset($json->flags->$user)) die("This user hasn't raised a flag");

	unset($json->flags->$user);
	if (count((array) $json->flags) == 0) unset($json->flags);
	fwrite(fopen('config.json', 'w+'), json_encode($json));
} else {
<<<<<<< HEAD
	chdir(__DIR__ . '/../../');
	$flags = json_decode(file_get_contents('postflags.json'));
	unset($flags->{$_GET['room']}->{$_GET['post']}->{$_GET['user']});
	fwrite(fopen('postflags.json', 'w+'), json_encode($flags));
=======
	chdir(__DIR__ . '/../data/messages/' . $_GET['room']);
	$j = json_decode(file_get_contents('msg.json'));
	unset($j->{$_GET['id']}->flags->{$_GET['user']});
	fwrite(fopen('msg.json', 'w+'), json_encode($j));
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
}
echo "Removed the flag";
?>
<p><a href="app/tools/flags.php">Return to flags</a></p>