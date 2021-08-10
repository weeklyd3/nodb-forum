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
	<base href="../../" />
	<title>About the Articles</title>
	<?php 
	include('../public/header.php');
	include('../styles/inject.php');
	?>
	</head>
	<body>
	<h2>Articles are like permanent information containers.</h2>
	because they exist indefinitely and cannot be deleted by anyone.
	<p>The data is stored as JSON, making it easy to read or write from. Mknifying the JSON also saves disk space so there is more room for new articles.</p>
	<details>
	<summary>Raw data</summary>
	<pre style="word-break:break-word;"><code class="lang-json"><?php 
		$stuff = new stdClass;
		if ($array = array_diff(scandir('./content'), array('.', '..'))) {
			natcasesort($array);
			foreach ($array as $entry)
				$stuff->$entry = json_decode(file_get_contents('./content/'.$entry.'/config.json'));
			echo htmlspecialchars(json_encode($stuff));
		}
	?></code></pre>
	</details>
	<script>hljs.highlightAll();</script>
