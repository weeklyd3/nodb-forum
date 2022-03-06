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
	include('./public/header.php');
	include('./styles/inject.php');
	?>
    <title>Search with Tags</title>
  </head>
  <body>
<form action="search.php" method="GET">
	<fieldset><legend>Tags to add</legend>
	<label>Search for this: <input type="search" name="query" /></label>... <label>With these space-separated tags: <input type="text" name="tags" /></label>
	<input type="submit" value="Search" /></fieldset>
</form>
	  <p>Tags you search for will show up in the search box.</p>
<img src="img/tagsearch.png" style="max-width:100%;" alt="Tag search" />