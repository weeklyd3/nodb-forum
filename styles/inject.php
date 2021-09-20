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
?><!-- Why not take this for some ice 
cream⸮ It's only a prick!

Why not⸮ People are harmed more than
helped by this! -->
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
echo '<link rel="stylesheet" href="./styles/main/main.css" />';
if (file_exists(__DIR__ . '/../config.json')) {
	$config = json_decode(file_get_contents(__DIR__ . '/../config.json'));
	if (!isset($config->admins)) $config->admins = array('admin');

	fwrite(fopen(__DIR__ . '/../config.json', 'w+'), json_encode($config));
}
?>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/vs.min.css" />
<!-- highlight.js -->
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script>
console.log('%cIf you are pasting code here others could be trying to make you do Self XSS. ', 'color:red;font-size:2em;');
console.log("%cWhat could attackers do when you paste their code? A lot of evil things. Such as:\n - Logging a user out\n - Creating random topics without user interaction\n - or even send messages without user consent!\nSo don't paste any code unless you absolutely know what you're doing!", 'color:#d3b312;font-size:1.2em;')
console.log("%cIf you're debugging you're fine", 'color:blue;font-size:1.5em;');
window.addEventListener('DOMContentLoaded', function() {
	hljs.highlightAll();
});
</script>