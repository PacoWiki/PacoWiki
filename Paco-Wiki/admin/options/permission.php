<table class="dtable-table">
  <tr>
    <th></th>
    <th>Create Wiki</th>
    <th>Edit Other's</th>
    <th>Delete Other's</th>
    <th>Edit Own Post</th>
    <th>Delete Own Post</th>
    <th>Edit</th>
    <th>Delete</th>
    <th>Read</th>
  </tr>
<?php
$roles = pacowiki_get_all_roles();
foreach($roles as $key => $role){

	if($key=='administrator')
		$is_admin = true;
	else
		$is_admin = false;
	$capabilities = PacoWiki()->get_capabilities($key);
?>
  <tr>
    <td><?php echo $role["name"]; ?></td>
    <td data-th="Create Wiki"> 		<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][publish_posts]" <?php echo ( @$capabilities['publish_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?>/></td>
    <td data-th="Edit Other's"> 	<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][edit_others_posts]"  <?php echo ( @$capabilities['edit_others_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Delete Other's"> 	<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][delete_others_posts]"  <?php echo ( @$capabilities['delete_others_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Edit Own Post"> 	<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][edit_published_posts]"  <?php echo ( @$capabilities['edit_published_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Delete Own Post"> 	<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][delete_published_posts]"  <?php echo ( @$capabilities['delete_published_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Edit"> 			<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][edit_posts]"  <?php echo ( @$capabilities['edit_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Delete">	 		<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][delete_posts]"  <?php echo ( @$capabilities['delete_posts'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
    <td data-th="Read"> 			<input type="checkbox" name="pacowiki_options[pacowiki_cap_<?php echo $key; ?>][read]"  <?php echo ( @$capabilities['read'] ? ' value="1" checked=""' : '') ?> <?php echo ($is_admin ? ' disabled="disabled"' : '') ?> /></td>
  </tr>
<?php
}
?> 
 </table>
