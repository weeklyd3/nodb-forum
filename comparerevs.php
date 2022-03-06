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
    <title>Compare Revisions</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <?php 
  if (!isset($_POST['old'], $_POST['new'])) die("Not all parameters specified.");

  $old = json_decode($_POST['old']);
  $new = json_decode($_POST['new']);
  if (!(is_string($old) && is_string($new))) die("Malformed input.");
  ?>
  <h2>Comparison of provided strings</h2>
  <table>
  <tr>
  <th>Old</th>
  <th>New</th>
  </tr>
  <tr>
  <td>
  <pre><?php echo htmlspecialchars($old); ?></pre></td>
  <td>
  <pre><?php echo htmlspecialchars($new); ?></pre></td>
  </tr>
  </table><?php
  require 'libraries/diff.php';
  diff($old, $new);