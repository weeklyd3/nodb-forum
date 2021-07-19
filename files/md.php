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
	<base href=".." />
    <title>Forums &mdash; Markdown</title>
	<?php
	include('../styles/inject.php');
	?>
  </head>
  <body>
    <main>
		<div id="formatting">
			<h2>Formatting</h2>
			<div id="bold">
				To bold,
				<ul>
					<li><code>**<strong>bold text</strong>**</code></li>
					<li><code>__<strong>bold text</strong>__</code></li>
					<li><code>&lt;strong><strong>bold text</strong>&lt;/strong></code></li>
				</ul>
			</div>
			<div id="italic">
				To emphasize (or italic):
				<ul>
					<li><code>*<em>italic text</em>*</code></li>
					<li><code>_<em>italic text</em>_</code></li>
					<li><code>&lt;em><em>italic text</em>&lt;/em></code></li>
				</ul>
			</div>
			<div id="bolditalic">
				You can combine it, but use bold and italic together sparingly!
				<ul>
					<li><code>***<strong><em>bold and italic</em></strong>***</code></li>
					<li><code>___<strong><em>bold and italic</em></strong>___</code></li>
					<li>Tag order doesn't matter: <code>&lt;strong>&lt;em><strong><em>bold and italic</em></strong>&lt;/em>&lt;/strong></code></li>
				</ul>
			</div>
			<div id="script">
				<div id="sub">
					Use the sub tag for a subscript:
					<code><em>i</em>&lt;sub><sub>2</sub>&lt;/sub></code>
				</div>
				<div id="sup">
					Use the sup tag for a superscript:
					<code><em>i</em>&lt;sup><sup>2</sup>&lt;/sup> = -1.</code>
				</div>
			</div>
			<div id="hr">
				Use three dashes for a horizontal line:
				<code>---</code>
			</div>
			<div id="code">
				<h2 id="block">Code</h2>
				You can indent by four spaces: <br /><code><span style="background-color:gray;">&nbsp;&nbsp;&nbsp;&nbsp;</span>#include &lt;stdio.h><br /><span style="background-color:gray;">&nbsp;&nbsp;&nbsp;&nbsp;</span>int main() {<br /><span style="background-color:gray;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;printf("Hello World");<br /><span style="background-color:gray;">&nbsp;&nbsp;&nbsp;&nbsp;</span>}</code>
				<br />
				Or you can use GitHub flavored markdown:
				<pre>```<br />#include <?php echo htmlspecialchars('<stdio.h>'); ?><br />int main() {<br />    printf("Hello World");<br />}<br />```</pre>
				If you don't want a code block, just use a single <code id="inlinecode">`</code>:
				<code>You can use a `div` tag for this.</code>
			</div>
			<div>
				<h2 id="link">Links</h2>
				There are many ways:
				<ul>
					<li><pre>Link to [home page](http://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>)</pre></li>
					<li><pre>Link to [home page][1]<br /><br />[1]: http://<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?></pre>
					Note that the blank line between [1]: and [home page][1] is not required, but it increases readability.</li>
					<li>The best one:<pre>Link to [home page][home]<br /><br />[home]: http://<?php echo $_SERVER['HTTP_HOST']; ?></pre></li>
					<li>When all else fails:<pre><?php echo htmlspecialchars('Link to <a href="'.htmlspecialchars($_SERVER['HTTP_HOST']) . '">home page</a>'); ?></pre></li>
				</ul>
			</div>
			<div>
				<h2>Other Things</h2>
				<ul>
					<li>Sliding text (bad for screen readers): <pre><?php echo htmlspecialchars('<marquee>Wheeeee!!!</marquee>'); ?></pre></li>
					<li>Keyboard keys: <pre><?php echo htmlspecialchars('<kbd>ctrl</kbd>+<kbd>alt</kbd>+<kbd>del</kbd>'); ?></pre> renders as <kbd>ctrl</kbd>+<kbd>alt</kbd>+<kbd>del</kbd>.</li>
				</ul>
			</div>
		</div>
	</main>