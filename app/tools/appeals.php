<?php
include('header.php');
if (!file_exists(__DIR__ . '/../../extensions/nodb-forum-ban-appeal/appeals.php')) {
	die("Sorry, the Ban Appeal extension is not installed. Please see <a href=\"https://github.com/weeklyd3/nodb-forum/wiki/Plugins\">the wiki page</a> for how to install plugins.");
} else {
	include(__DIR__ . '/../../extensions/nodb-forum-ban-appeal/appeals.php');
}