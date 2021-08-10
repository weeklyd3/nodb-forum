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
?>
<table id="header" style="width:100%; position:sticky; top:0px; background-color:blue;z-index:10;">
<tr><td><h1 style="display:inline;"><span id="menubutton" style="cursor:pointer;"><img src="img/menu.png" alt="≡" /></span> <a style="text-decoration:none !important;" href="./"><img alt="Forum Logo" src="./img/logo.png" /><span id="TitleText">
<?php
include(__DIR__ . '/../libraries/lib.php');
if (!file_exists(__DIR__ ."/../config.json")) {
	header("Location: /app/install.php");
}
function getname() {
	if (isset($_COOKIE['login'])) {
		$COOK = $_COOKIE['login'];
		$STATS = explode("\0", $COOK);
		$path = cleanFilename($STATS[0]);
		$path = __DIR__ . '/../data/accounts/'.$path;
		$hash = file_get_contents($path . '/psw.txt');
		if ($COOK != '') {
			if (password_verify($STATS[1], $hash)) {
				$match = true;
			} else {
				header('Location: '.$SERVER['DOCUMENT_ROOT'].'/invalidpass.php');
			}
			return $STATS[0];
		}
	}
}
$object = json_decode(file_get_contents(__DIR__ ."/../config.json"));
echo $object->forumtitle."</span></a></h1></td>";
echo '<td><a href="explorer/">source code</a></td>';
if (!getname()) {
	echo '<td><a href="account/signup.php">sign up</a></td>';
	echo '<td><a href="account/login.php">log in</a></td>';
} else {
	echo '<td><a href="account/">'.htmlspecialchars(getname()).'</a></td>';
	echo '<td>(<a href="account/logout.php">log out</a>)</td></tr>';
}
if (file_exists(__DIR__ . '/../data/accounts/'.cleanFilename(getname()).'/ban.txt') && $_SERVER['REQUEST_URI'] != '/banned.php') {
	header('Location: /banned.php');
}
?>
<script>
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
<tr><td>&nbsp;</td><td rowspan="2" align="right"><form action="search.php" method="GET"> <input type="search" id="query" name="query" placeholder="search rooms" /> <input type="submit" value=">" /></form></td><td></td></tr>
</table>
<div id="banner" style="width:100%;background-color:gold;color:black;text-align:center;"><?php 
require(__DIR__ . '/../libraries/parsedown.php');
	if (isset($object->banner)) {
		$parse = new Parsedown;
		echo $parse->text($object->banner);
		?><a href="javascript:;" onclick="this.parentNode.style.display = 'none';">&times;</a><?php
	}
?></div>
<div id="menu" style="resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#e2ccf5; display:none; position:fixed; top:0; left:0; overflow-y:scroll;">
<p id="drag" style="text-align:right;cursor:move;"><span style="text-align:left;">Menu</span> <span onclick="document.getElementById('menu').style.display='none';" style="cursor:pointer;">&times;</span></p>
<ul style="list-style:none; padding:7px;">
<li><a href="./">Home</a></li>
<li><a href="./webchat.php">Chat room</a></li>
<li><a href="./articles">Articles</a>
<br>
<li><a href="./account/">My Account</a></li>
<li><a href="./account/login.php">Log in to different account</a></li>
<li><a href="./account/signup.php">Sign up for account</a></li>
<br>
<li><a href="./explorer">File Explorer</a></li>
<li><a href="https://github.com/weeklyd3/nodb-forum">GitHub repository</a></li>
<li><a href="app/tools/">Moderation Tools</a></li>
</ul>
<center style="word-break:break-all;"><em><?php
$link = 'Location: ';
$link .= $_SERVER['HTTP_HOST'];
$link .= $_SERVER['REQUEST_URI'];
echo $link;
?><br><br>
<strong><a href="javascript:;" onclick="document.getElementById('menu').setAttribute('style', 'resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#e2ccf5; display:block; position:fixed; top:0; left:0; overflow-y:scroll;');">Reset menu position</a></strong></em></center>
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