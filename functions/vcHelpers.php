<?php
/**
 * -----------------------------------------------------------------------------
 * Visual Composer Helpers
 * -----------------------------------------------------------------------------
 * This class provides helper functions for visual composer integration.
 * -----------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * Stop Direct Access.
 * -----------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * The Class
 * -----------------------------------------------------------------------------
 */
class cofl_vcHelpers extends cofl_shortcodeHelpers
{
	/**
	 * -----------------------------------------------------------------------------------------------
	 * Get Post types
	 * -----------------------------------------------------------------------------------------------
	 * gets a list of all registered post types that are not built in to Wordpress for use with Visual
	 * Composer VC Map function. The function also merges an array of the built in post types we do
	 * want, which in this case are just posts as they are pretty important.
	 *
	 * This function uses the built in Wordpress get_post_types.
	 * @see https://codex.wordpress.org/Function_Reference/get_post_types
	 * @return array $postTypes.
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_getPostTypes()
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

}//end class