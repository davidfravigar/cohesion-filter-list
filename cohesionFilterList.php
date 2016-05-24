<?php
/**
 * -----------------------------------------------------------------------------
 * Wordpress Stuff
 * -----------------------------------------------------------------------------
 * Plugin Name: 	Filter List
 * Plugin URI: 		http://davidfravigar.com/
 * Description: 	A Shortcode for displaying a post filter
 * Version: 			0.0.1
 * Author: 				David Fravigar
 * Author URI: 		http://davidfravigar.com/
 * License: 			MIT
 * -----------------------------------------------------------------------------
 * Developer Stuff
 * -----------------------------------------------------------------------------
 * Filter posts using Ajax and magic.
 *
 * @author 		David Fravigar
 * @version 	0.0.1
 * -----------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * Stop Direct Access
 * -----------------------------------------------------------------------------
 */
if (!defined('ABSPATH')) {
  die('This is not the script you are looking for.... move along');
}

class FilterList
{
	/**
	 * ---------------------------------------------------------------------------
	 * Vars
	 * ---------------------------------------------------------------------------
	 */

	/**
	 * ---------------------------------------------------------------------------
	 * Constructor
	 * ---------------------------------------------------------------------------
	 */
	function __construct()
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php');
		$this->cofl_constants();
		$this->cofl_addShortcode();
		add_action('wp_enqueue_scripts', array($this, 'cofl_enqeueMedia'));
	}//end constructor

	/**
	 * ---------------------------------------------------------------------------
	 *
	 * ---------------------------------------------------------------------------
	 */
	function cofl_constants()
	{
		define('FILTERLIST_DIR', plugin_dir_path(__FILE__));
		define('FILTERLIST_FUNCTIONS_DIR',FILTERLIST_DIR . 'functions');
		define('FILTERLIST_VIEWS_DIR',FILTERLIST_DIR . 'views');

		define('FILTERLIST_URL', plugin_dir_url(__FILE__));
		define('FILTERLIST_ASSETS_PATH', FILTERLIST_URL . 'assets');
	}

	/**
	 * ---------------------------------------------------------------------------
	 * add shortcode function
	 * ---------------------------------------------------------------------------
	 * This function checks to see if the Visual Composer theme is present. If it
	 * is the shortcode is registered to work with visual composer, if not a plain
	 * old Wordpress shortcode is used.
	 * ---------------------------------------------------------------------------
	 */
	public function cofl_addShortcode()
	{
		if(is_plugin_active('js_composer_theme/js_composer.php')) {
			require_once(FILTERLIST_FUNCTIONS_DIR . '/shortcodeHelpers.php');
			add_action('vc_before_init', array($this, 'cofl_registerVisualComposerShortcode'));
		} else {
			add_shortcode('filter_list', array($this, 'cofl_filterList'));
			//add info page.
		}
	}

	/**
	 * ---------------------------------------------------------------------------
	 * Visual composer mapper function
	 * ---------------------------------------------------------------------------
	 */
	function cofl_registerVisualComposerShortcode()
	{
		vc_map(array(
			'name'					=> __('Filter List', 'cohesion'),
			'base'					=> 'cofl_filterList',
			'category' 			=> __('Cohesion', 'cohesion'),
			'params' => array(
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Post Type', 'cohesion'),
					'param_name' 	=> 'post_type',
					'value'				=> cofl_shortcodeHelpers::getPostTypes(),
					'description' => __('Select the post type to display in this element', 'cohesion'),
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Number of entries to show', 'cohesion'),
					'param_name' 	=> 'amount',
					'value'				=> '10',
					'description' => __('Select the amount of entries to display', 'cohesion'),
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Categories to include', 'cohesion'),
					'param_name' 	=> 'included_categories',
					'value'				=> '',
					'description' => __('Enter the categories you would like to display, separated with a comma. If left blank all categories will be used', 'cohesion'),
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Categories to exclude', 'cohesion'),
					'param_name' 	=> 'included_categories',
					'value'				=> '',
					'description' => __('Enter the categories you do not want to display, separated with a comma', 'cohesion'),
				),
			)
		));
	}

	/**
	 * ---------------------------------------------------------------------------
	 *
	 * ---------------------------------------------------------------------------
	 */
	public function cofl_enqueueMedia()
	{

	}

	/**
	 * ---------------------------------------------------------------------------
	 * Shortcode function
	 * ---------------------------------------------------------------------------
	 */
	public function cofl_filterList($atts, $content = null)
	{
		
	}//end filterList
}//end class

/**
 * -----------------------------------------------------------------------------
 * instantiate the class
 * -----------------------------------------------------------------------------
 */
new FilterList();