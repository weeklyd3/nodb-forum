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
	?>
	<h1>Install!</h1>
	<p>It looks like your discussion board has not been set up yet! That's fine, you can set it up by completing the form below.</p>
	<p>If you think you already installed the software and you see this screen after reloading, there's something wrong with the installation. Please open an issue on GitHub: weeklyd3/nodb-forum.</p>
	<form action="app/welcome.php" method="post">
	<label for="title">Type the title:</label>
	<input type="text" id="title" name="title" required="required" value="Forums &lt;small&gt;Beta&lt;/small&gt;" size="35"/>
	<br />
	HTML will <strong>not</strong> be escaped! This allows rich text formatting.
	<hr />
	<label>These are the tags. Type them, separated by spaces.
	<br />
	<input type="text" name="tags" style="width: 100%;" placeholder="javascript python php" />
	</label>
	<hr />
	<input type="submit" value="install" /></form></bodybody></html>