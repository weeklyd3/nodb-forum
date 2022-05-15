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
    <title>Installed Software</title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
    <h2>Version Information</h2>
<table>
    <tr>
        <th scope="row">nodb-forum installed:</th>
        <td><?php echo file_exists('config.json') ? 'Yes' : 'No, please <a href="app/install.php">set it up first</a>'; ?></td>
    </tr>
    <tr>
        <th scope="colgroup" colspan="2">Installed Plugins (<?php echo count(scandir('extensions/', SCANDIR_SORT_NONE)) - 3; ?>)</th>
    </tr>
    <?php 
$ext = scandir('extensions/');
foreach ($ext as $p) {
    if (in_array($p, array('.', '..', 'readme.txt'))) {
        continue;
    }
        $c = json_decode(file_get_contents("plugins/$p/config.json"));
    ?>
        <tr>
            <th scope="colgroup">Plugin: <?php echo $c->name; ?></th>
        </tr><?php
    foreach ((array) $c as $prop => $value) {
        ?><tr>
            <th scope="col"><?php echo htmlspecialchars($prop); ?></th>
            <td><?php echo htmlspecialchars(json_encode($value)); ?></td>
        </tr><?php
    }
}
?>
</table>
