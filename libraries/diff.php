<?php
/* A very ambitious attempt at a diff program
 in PHP. LICENSE file still applies. */
function diff(string $old, string $new) {
    if ($old === $new) { ?>Nothing was changed.<?php return;}
    foreach (array(&$old, &$new) as &$string) {
        $string = explode("\n", $string);
    }
    ?>
	<table class="table">
		<tr><th>Original</th><th>Comment</th><th>New</th></tr>
		<?php
  $maxcount = max(array(count($old), count($new)));
  for ($i = 0; $i < $maxcount; $i++) { 
			$neutralize = false;	
	  if (isset($old[$i], $new[$i])) {
		  if ($old[$i] === $new[$i]) {
			  $neutralize = true;
		  }
	  }
	  ?>
		<tr>
				 <td<?php
	  if (!$neutralize) {
		  ?> class="delete-string"<?php
	  }
	  ?>><del><?php
     // 0 = modified or not, -1 = added, 1 = deleted
     $comments = array("added", "kept", "deleted");
     $status = 0;
     if ($i >= count($old)) {
         $status = -1;
     } else {
         echo htmlspecialchars($old[$i]);
     }
     if ($i >= count($new)) {
         $status = 1;
     }
     ?></del></td>
				 <td class="diff-comment"><?php echo $comments[$status + 1]; ?> -></td>
				 <td<?php
	  if (!$neutralize) {
		  ?> class="add-string"<?php
	  }
	  ?>><ins><?php if ($status !== 1) {
         echo htmlspecialchars($new[$i]);
     } ?></ins></td>
			 </tr><?php }?>
	</table><?php
}
