<?php
/*
    Forum Software
    Copyright (C) 2022 contributors

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
register_shutdown_function(function() {
	require_once __DIR__ . '/footer.php';
});
?>
<div id="keyboard-focus" style="z-index:999;">
	<a id="link" href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#mainContent" style="background-color:black;">Skip to main content</a>
</div>
  <div id="inbox-blanket" class="blanket">
	<div class="overlay" id="inbox-overlay" style="display:none;position:fixed;top:50%;left:50%;transform: translate(-50%,-50%); ">
		<table height="100%"><tr><td><p id="loading" style="color:black;">loading...</p><iframe src="about:blank" onload="document.getElementById('loading').style.display = 'none';" id="notification-iframe" frameBorder="0" style="height:600px;max-height:75vh;"></iframe></td></tr><tr><td><button onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.style.display = 'none';document.getElementById('loading').style.display = 'block';document.getElementById('notification-iframe').src = '';">Close</button></td></tr></table>
	</div>
  </div>
<table id="header" style="width:100%; max-width:100%;background-color:#7AD7FF;color:black;z-index:10;">
<tr><td><h1 style="display:inline;"><span id="menubutton" style="cursor:pointer;" tabindex="0"><img src="img/menu.png" alt="" /></span> <a style="text-decoration:none !important;" href="./"><img alt="Forum Logo" src="./img/logo.png" /><span id="TitleText">
<?php
include_once(__DIR__ . '/../libraries/lib.php');
if (!file_exists(__DIR__ ."/../config.json")) {
	header("Location: /app/install.php");
}
$object = json_decode(file_get_contents(__DIR__ ."/../config.json"));
echo $object->forumtitle."</span></a></h1></td>";
if (!getname()) {
	echo '<td><a href="account/signup.php">sign up</a></td>';
	echo '<td><a href="account/login.php">log in</a></td>';
} else {
	$unread = '>';
	function determineIfUnread() {
		$n = json_decode(file_get_contents(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . '/inbox.json'));
		foreach ($n->items as $item) {
			if (!$item->read) {
				return true;
			}
		}
		return false;
	}
	if (determineIfUnread()) $unread = ' style="background-color:#ff0000;font-weight:bold;" title="New notifications.">See new ';
	echo '<td><a href="inbox.php" id="notification-button"' . $unread . 'notifications</a></td><td><a href="account/">'.htmlspecialchars(getname()).'</a></td>';
	echo '<td id="personal-links"><a href="account/viewuser.php?user=' . htmlspecialchars(urlencode(getname())) . '">profile</a> | <a href="messages/">mail</a> | <a href="account/pages/index.php" le="subpages">subpages</a></td><td>(<a href="account/logout.php">log out</a>)</td></tr>';
}
?>
<script>
	if (document.getElementById('notification-button') != null) {
		document.getElementById('notification-button').addEventListener('click', function(event) {
			document.getElementById('notification-iframe').src = '';
			event.preventDefault();
			document.getElementById('inbox-overlay').style.display = 'block';
			document.getElementById('loading').style.display = 'block';
			document.getElementById('notification-iframe').src = 'inbox.php';
		});
	}
	document.querySelector('html')
		.addEventListener('keydown',
			function(event) {
				if (!(document.activeElement instanceof HTMLInputElement || document.activeElement instanceof HTMLTextAreaElement) && event.key == '/') {
					event.preventDefault();
					document.querySelector('input[type=search]').focus();
				}
			}
		);
</script>
<tr><td rowspan="4" align="right"><form action="search.php" method="GET"><label for="query" style="display:none;">Search query:</label> <span style="padding:7px;border:1px solid; color: black; background-color: white;">
	<?php if (!isset($_GET['tags'])) { ?><span class="tag nohash"><a href="tagsearch.php" title="Add tags to search">[no tags]</a></span> <?php } 
else {
	$tags = array_unique(array_filter(explode(" ", $_GET['tags']), function($m) { return $m !== ""; }));
	foreach ($tags as $tag) {
		?><span class="tag"><?php echo htmlspecialchars($tag); ?></span> <?php
	}
	?><small><i><a href="tagsearch.php">(change)</a> <a title="Remove all tags and search" href="search.php?query=<?php echo htmlspecialchars($_GET['query']); ?>">(remove)</a></i></small>
	
	<input type="hidden" name="tags" value="<?php echo htmlspecialchars(implode(" ", $tags)); ?>" /><?php
}
	?>&nbsp;<input style="outline:none;padding:0;border:none;" type="search" id="query" name="query" placeholder="search rooms" value="<?php if (isset($_GET['query'])) { echo htmlspecialchars($_GET['query']); } ?>" /> </span><input type="submit" value=">" /></form></td><td></td></tr>
</table>
<div id="banner" style="width:100%;background-color:gold;color:black;text-align:center;"><?php 
require(__DIR__ . '/../libraries/parsedown.php');
	if (isset($object->banner)) {
		$parse = new Parsedown;
		echo $parse->line($object->banner);
		?> <a href="javascript:;" onclick="this.parentNode.style.display = 'none';">&times;</a><?php
	}
?></div>
<div id="menu" style="resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#e2ccf5; display:none; position:fixed; top:0; left:0; overflow-y:scroll;color:black;">
<p id="drag" style="text-align:right;cursor:move; clear: both;"><span style="float:left;">Menu</span> <span onclick="document.getElementById('menu').style.display='none';" style="float: right;cursor:pointer;">&times;</span></p><br />
<ul style="list-style:none; padding:7px;">
<li><a href="./">Home</a></li>
<li><a href="./viewtopic.php">Chat room</a></li>
<li><a href="./articles">Articles</a>
<hr />
<?php if (getname()) { ?>
<li><a href="./account/">My Account</a></li>
<li><a href="./messages/">Mail</a></li>
<li><a href="account/pages">Subpages</a></li>
<li><a href="account/logout.php">Log out</a></li>
	<?php } else { ?> 
<li><a href="account/login.php">Log In</li>
<li><a href="account/signup.php">Sign Up</a></li>
<li><a href="account/resetpass.php">Reset password</a></li>
<?php } 
if (verifyAdmin()) { ?>
<hr>
<li><a href="app/tools/">Moderation Tools</a></li>
	<?php } ?>
</ul>
<center style="word-break:break-all;"><em><?php
$link = 'Location: ';
$link .= $_SERVER['HTTP_HOST'];
$link .= $_SERVER['REQUEST_URI'];
echo $link;
?><br><br>
<strong><a href="javascript:;" onclick="document.getElementById('menu').setAttribute('style', 'resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#e2ccf5; display:block; position:fixed; top:0; left:0; overflow-y:scroll;color:black;');">Reset menu position</a></strong></em></center>
</div>
<script>
document.querySelector("#query").onfocus = function() {
	document.getElementById('query').setAttribute(
		"placeholder",
		"type your search"
	);
}
document.querySelector("#query").onblur = function() {
	document.getElementById('query').setAttribute(
		"placeholder",
		"search rooms"
	);
}
document.getElementById('menubutton').addEventListener('click', function() {
	if (document.getElementById('menu').style.display=='none') {
		document.getElementById('menu').style.display = 'block';
	} else {
		document.getElementById('menu').style.display = 'none';
	}
});
window.addEventListener('click', function(e) {   
  if (!document.getElementById('menu').contains(e.target) && document.getElementById('menu').display == 'block') {
	  document.getElementById('menu').style.display = 'none';
  }
});
	function makeDraggable(dragHandle, dragTarget) {
  let dragObj = null; 
  let xOffset = 0;
  let yOffset = 0;

  document.querySelector(dragHandle).addEventListener("mousedown", startDrag, true);
  document.querySelector(dragHandle).addEventListener("touchstart", startDrag, true);

  function startDrag(e) {
    e.preventDefault();
    e.stopPropagation();
    dragObj = document.querySelector(dragTarget);
    dragObj.style.position = "absolute";
    let rect = dragObj.getBoundingClientRect();

    if (e.type=="mousedown") {
      xOffset = e.clientX - rect.left; 
      yOffset = e.clientY - rect.top;
      window.addEventListener('mousemove', dragObject, true);
    } else if(e.type=="touchstart") {
      xOffset = e.targetTouches[0].clientX - rect.left;
      yOffset = e.targetTouches[0].clientY - rect.top;
      window.addEventListener('touchmove', dragObject, true);
    }
  }

  function dragObject(e) {
    e.preventDefault();
    e.stopPropagation();

    if(dragObj == null) {
      return;
    } else if(e.type=="mousemove") {
      dragObj.style.left = e.clientX-xOffset +"px";
      dragObj.style.top = e.clientY-yOffset +"px";
    } else if(e.type=="touchmove") {
      dragObj.style.left = e.targetTouches[0].clientX-xOffset +"px";
      dragObj.style.top = e.targetTouches[0].clientY-yOffset +"px";
    }
  }

  document.onmouseup = function(e) {
    if (dragObj) {
      dragObj = null;
      window.removeEventListener('mousemove', dragObject, true);
      window.removeEventListener('touchmove', dragObject, true);
    }
  }
}

makeDraggable("#drag", "div");
</script>
<div id="mainContent">