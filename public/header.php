<?php
/*
    Forum Software
    Copyright (C) 2021 weeklyd3

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
?><?php
include($_SERVER["DOCUMENT_ROOT"] . '/libraries/lib.php');
if (!file_exists($_SERVER['DOCUMENT_ROOT']."/config.json")) {
	header("Location: /app/install.php");
}
error_reporting(0);
echo '<table style="width:100%; position:sticky; top:0; background-color:blue;z-index:10;">';
function getname() {
	$COOK = $_COOKIE['login'];
	$STATS = explode("\0", $COOK);
	$path = cleanFilename($STATS[0]);
	$path = $_SERVER['DOCUMENT_ROOT'].'/data/accounts/'.$path;
	$hash = file_get_contents($path . '/psw.txt');
	if ($COOK != '') {
		if (password_verify($STATS[1], $hash)) {
			$match = true;
		} else {
			//header('Location: ./../invalidpass.php');
		}
		return $STATS[0];
	}
}
$object = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/config.json"));
echo "<tr><td><h1 style=\"display:inline;\"><span id=\"menubutton\" style=\"cursor:pointer;\">☰</span> <a style=\"text-decoration:none !important;\" href=\"./\"><img alt=\"Forum Logo\" src=\"./img/logo.png\" />".$object->forumtitle."</a></h1></td>";
$login = $_COOKIE['login'];
echo '<td><a href="explorer/">source code</a></td>';
if ($login == "") {
	echo '<td><a href="account/signup.php">sign up</a></td>';
	echo '<td><a href="account/login.php">log in</a></td>';
} else {
	echo '<td>Logged in as: '.htmlspecialchars(getname()).'</td>';
	echo '<td><a href="webchat.php">web chat</a> (<a href="account/">Account Options</a> | <a href="account/logout.php">log out</a>)</td>';
}
echo "</table>";
?>
<div id="menu" style="resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#00dddd; display:none; position:fixed; top:0; left:0; overflow-y:scroll;">
<p id="drag" style="text-align:right;cursor:move;"><span style="text-align:left;">Menu</span> <span onclick="document.getElementById('menu').style.display='none';" style="cursor:pointer;">&times;</span></p>
<ul style="list-style:none; padding:7px;">
<li><a href="./">Home</a></li>
<li><a href="./webchat.php">Chat room</a></li>
<br>
<li><a href="./account/">My Account</a></li>
<li><a href="./account/login.php">Log in to different account</a></li>
<li><a href="./account/signup.php">Sign up for account</a></li>
<br>
<li><a href="./data/messages/webchat.txt">Chat messages - raw</a></li>
<li><a href="./explorer">File Explorer</a></li>
<li><a href="https://github.com/weeklyd3/nodb-forum">GitHub repository</a></li>
</ul>
<center style="word-break:break-all;"><em><?php
$link = 'Location: ';
$link .= $_SERVER['HTTP_HOST'];
$link .= $_SERVER['REQUEST_URI'];
echo $link;
?><br><br>
<strong><a href="javascript:;" onclick="document.getElementById('menu').setAttribute('style', 'resize:both; max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#00dddd; display:block; position:fixed; top:0; left:0; overflow-y:scroll;');">Reset menu position</a></strong></em></center>
</div>
<script>
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
  let dragObj = null; //object to be moved
  let xOffset = 0; //used to prevent dragged object jumping to mouse location
  let yOffset = 0;

  document.querySelector(dragHandle).addEventListener("mousedown", startDrag, true);
  document.querySelector(dragHandle).addEventListener("touchstart", startDrag, true);

  /*sets offset parameters and starts listening for mouse-move*/
  function startDrag(e) {
    e.preventDefault();
    e.stopPropagation();
    dragObj = document.querySelector(dragTarget);
    dragObj.style.position = "absolute";
    let rect = dragObj.getBoundingClientRect();

    if (e.type=="mousedown") {
      xOffset = e.clientX - rect.left; //clientX and getBoundingClientRect() both use viewable area adjusted when scrolling aka 'viewport'
      yOffset = e.clientY - rect.top;
      window.addEventListener('mousemove', dragObject, true);
    } else if(e.type=="touchstart") {
      xOffset = e.targetTouches[0].clientX - rect.left;
      yOffset = e.targetTouches[0].clientY - rect.top;
      window.addEventListener('touchmove', dragObject, true);
    }
  }

  /*Drag object*/
  function dragObject(e) {
    e.preventDefault();
    e.stopPropagation();

    if(dragObj == null) {
      return; // if there is no object being dragged then do nothing
    } else if(e.type=="mousemove") {
      dragObj.style.left = e.clientX-xOffset +"px"; // adjust location of dragged object so doesn't jump to mouse position
      dragObj.style.top = e.clientY-yOffset +"px";
    } else if(e.type=="touchmove") {
      dragObj.style.left = e.targetTouches[0].clientX-xOffset +"px"; // adjust location of dragged object so doesn't jump to mouse position
      dragObj.style.top = e.targetTouches[0].clientY-yOffset +"px";
    }
  }

  /*End dragging*/
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