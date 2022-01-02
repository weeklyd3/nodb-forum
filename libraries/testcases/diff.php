<base href="../../" />
<?php 
require '../diff.php';
require '../../styles/inject.php';
?>
<h2>Nothing changed</h2><?php
diff('foo', 'foo');
?>
<h2>Changed</h2>
<?php 
diff('foo', 'bar');
?>
<h2>Added</h2>
<?php 
diff("foo\nbar", 'baz');
?>
<h2>Deleted</h2>
<?php 
diff('foo', "bar\nbaz");
?>
<h2>One Line Changed</h2>
<?php 
diff("foo\nbar", "foo\nbaz");
?>
<h2>Longer essay</h2>
<?php 
diff("Report:\nThis essay is about the effects of all the civilizations on the world. \nBecause there were so many civilizations, an essay on everything would be really long.\nThis is not a real essay, \njust a test 2 make sure the diff tool works.", "Report:\nThis essay is about the effects of many civilizations on the world. \nMaking all would be impossible.\nBecause there were so many civilizations, an essay on everything would be really long.\nThis is a fake essay. \nIt is a test to make sure the diff tool works.")
?>
<style>table.table, table.table>tbody>tr, table.table>tbody>tr>td, table.table>tbody>tr>th {
	border:1px solid;
	border-collapse: collapse;
}</style>