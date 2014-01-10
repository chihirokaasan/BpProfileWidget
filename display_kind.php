<?php
/*
Categories are displayed on a widget. 
*/
//include 'bp-user-search.php';
global $wpdb;
$parent = htmlspecialchars($par['parent']);
$childs = htmlspecialchars($par['child']);

$field = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM `wp_bp_xprofile_fields` 
	WHERE `name` LIKE '%s'
", $parent));

$kinds = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM `wp_bp_xprofile_fields` 
	WHERE `parent_id` = %d
", $field[0]->id));

$user_id = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM  `wp_bp_xprofile_data` 
	WHERE  `field_id` ={$field[0]->id}
	AND  `value` LIKE  '%%s%'
", $_GET['category_name']));

echo '<h3 class="widgettitle">'.$parent.'</h3>';
 foreach ($user_id as $user_id) {
	$include_user .= $user_id->user_id.',';
	}
	
$include_user = substr($include_user, 0, -1);

?>

<?php foreach($kinds as $kind):?>
<?php 

$group = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM  `wp_bp_xprofile_groups` 
	WHERE  `name` LIKE  '%s'
", $kind->name));

$janle = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM  `wp_bp_xprofile_fields` 
	WHERE  `group_id` =%d
	AND  `name` LIKE  '%s'
", $group[0]->id,$childs));

$janle_child = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM  `wp_bp_xprofile_fields` 
	WHERE  `group_id` = %d
	AND `name` LIKE  '%s'
", $janle[0]->group_id,$childs));

if(!empty($janle_child)){
	$kind_child = $wpdb->get_results($wpdb->prepare("
	SELECT * 
	FROM  `wp_bp_xprofile_fields` 
	WHERE  `parent_id` = %d
", $janle_child[0]->id));
}

?>
<li><a href="<?php get_option('siteurl') ;?>/members/?category_id=<?php echo $field[0]->id;?>&category_name=<?php echo $kind->name;?>"><?php echo $kind->name;?></a>
<?php if(isset($kind_child) && empty($kind_child)) :?>
<ul>
	<?php foreach($kind_child as $child):?>
	<li><a href="<?php get_option('siteurl') ;?>/members/?category_id=<?php echo $janle_child[0]->id;?>&category_name=<?php echo $child->name;?>"><?php echo $child->name;?></a></li>
    <?php endforeach;?>
</ul>
<?php endif;?>
</li>
<?php endforeach;?>