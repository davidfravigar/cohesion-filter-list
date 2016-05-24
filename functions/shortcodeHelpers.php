<?php

class cofl_shortcodeHelpers
{
	public static function getPostTypes()
	{
		$args = array(
   		'public'   => true,
   		'_builtin' => false
		);
		$output = 'names';
		$operator = 'and';
		$postTypes = array('posts');
		$postTypes = array_merge($postTypes, get_post_types($args, $output, $operator));
		return $postTypes;
	}
}