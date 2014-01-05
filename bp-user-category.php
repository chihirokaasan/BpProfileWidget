<?php
/*
Plugin Name: BPprofilewiget
Plugin URI: http://www.kaasan.info/
Description: BuddyPressの親要素を持つプロフィールデーターをウィジェットにして表示するプラグイン
Version: 0.1
Author: ITかあさん
Author URI: http://www.kaasan.info/
License: GPL
*/
add_action(
	'widgets_init',
	create_function('', 'return register_widget("MyWidget");')
);

/***
Action for members search.
This function searches menber by param in members page.
***/
add_action('bp_ajax_querystring','bpdev_include_users',20,1);
function bpdev_include_users($qs=false){
	global $wpdb;
	$user_id = $wpdb->get_results( "SELECT * 
	FROM  `wp_bp_xprofile_data` 
	WHERE  `field_id` ={$_GET['category_id']}
	AND  `value` LIKE  '%{$_GET['category_name']}%'" );
	$include_user = "";

	foreach ($user_id as $user_id) {
		$include_user .= $user_id->user_id.',';
	}
	$qs='include='.$include_user;
	return $qs;
	}

class MyWidget extends WP_Widget {
	function __construct() {
		$widget_ops = array('description' => 'BuddyPressのユーザープロフィールでカテゴリー分けするプラグインです');
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct(
			false,
			'BP Profile Category',
			$widget_ops,
			$control_ops
		);
	}

	public function form($par) {
	// Parent of category name.
		$paret = (isset($par['parent']) && $par['parent']) ? $par['parent'] : '';
		$id = $this->get_field_id('parent');
		$name = $this->get_field_name('parent');
		echo 'Parent：<br />';
		echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
		echo trim(htmlentities($paret, ENT_QUOTES, 'UTF-8'));
		echo '" />';
		echo '<br />';

	// Child of category name.
		$text = (isset($par['child']) && $par['child']) ? $par['child'] : '';
		$id = $this->get_field_id('child');
		$name = $this->get_field_name('child');
		echo 'Child：<br />';
		echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
		echo trim(htmlentities($paret, ENT_QUOTES, 'UTF-8'));
		echo '" />';
	}

	public function update($new_instance, $old_instance) {
		return $new_instance;
	}

	public function widget($args, $par) {
		include 'display_kind.php';
	}

}