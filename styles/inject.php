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
date_default_timezone_set('UTC');
set_error_handler(function($errno, $errstr, $errfile, $errline) {
	?><div><strong>[Debug info] Error:</strong><table class="table"><tr><th>Name</th><th>Value</th><tr><td>Error number</td><td><?php echo $errno; ?></td></tr><tr><td>Error string</td><td><?php echo htmlspecialchars($errstr); ?></td></tr><tr><td>File</td><td><?php echo $errfile; ?></td></tr><tr><td>Line</td><td><?php echo $errline; ?></td></table><a href="https://github.com/weeklyd3/nodb-forum/issues/new/choose">Report this error</a></div><?php
	return true;
}, E_ALL & ~E_NOTICE);
?><!-- Why not take this for some ice 
cream⸮ It's only a prick!

Why not⸮ People are harmed more than
helped by this! -->
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<?php
ini_set('display_errors', 0);
require_once __DIR__ . '/../libraries/lib.php';
echo '<link rel="stylesheet" href="./styles/main/main.css" />';
if (file_exists(__DIR__ . '/../config.json')) {
	$config = json_decode(file_get_contents(__DIR__ . '/../config.json'));
	if (!isset($config->admins)) $config->admins = array('admin');

	fwrite(fopen(__DIR__ . '/../config.json', 'w+'), json_encode($config));
}
	if (file_exists(__DIR__ . '/../data/accounts/' . cleanFilename(getname()) . "/increasedfontsize.txt")) {
		echo "<style>body { font-size: 125% !important; }</style>";
	}
?>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/vs.min.css" />
<!-- highlight.js -->
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
<script>
console.log('%cIf you are pasting code here others could be trying to make you do Self XSS. ', 'color:red;font-size:2em;');
console.log("%cWhat could attackers do when you paste their code? A lot of evil things. Such as:\n - Logging a user out\n - Creating random topics without user interaction\n - or even send messages without user consent!\nSo don't paste any code unless you absolutely know what you're doing!", 'color:#d3b312;font-size:1.2em;')
console.log("%cIf you're debugging you're fine", 'color:blue;font-size:1.5em;');
window.addEventListener('DOMContentLoaded', function() {
	hljs.highlightAll();
});
</script>
<?php 
require_once __DIR__ . '/../libraries/lib.php';
?>