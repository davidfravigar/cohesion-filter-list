<?php
/**
 * -------------------------------------------------------------------------------------------------
 * Wordpress Stuff
 * -------------------------------------------------------------------------------------------------
 * Plugin Name: 	Filter List
 * Plugin URI: 		http://davidfravigar.com/
 * Description: 	A Shortcode for displaying a post filter
 * Version: 			0.0.1
 * Author: 				David Fravigar
 * Author URI: 		http://davidfravigar.com/
 * License: 			MIT
 * -------------------------------------------------------------------------------------------------
 * Developer Stuff
 * -------------------------------------------------------------------------------------------------
 * Filter posts using Ajax and magic. The shortcode itself is compatible with visual composer. If no
 * visual composer is found then a plain old shortcode will be used.
 *
 * @author 		David Fravigar
 * @version 	0.0.1
 * -------------------------------------------------------------------------------------------------
 */

/**
 * -------------------------------------------------------------------------------------------------
 * Stop Direct Access
 * -------------------------------------------------------------------------------------------------
 */
if (!defined('ABSPATH')) {
  die('This is not the script you are looking for.... move along');
}

/**
 * -------------------------------------------------------------------------------------------------
 * The class
 * -------------------------------------------------------------------------------------------------
 */
class FilterList
{
	private $filterType;
	/**
	 * -----------------------------------------------------------------------------------------------
	 * Constructor
	 * -----------------------------------------------------------------------------------------------
	 */
	function __construct()
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php');
		$this->cofl_constants();
		$this->cofl_includes();
		$this->cofl_addShortcode();
		add_action('wp_enqueue_scripts', array($this, 'cofl_enqeueMedia'));
		add_action('wp_head', array($this, 'cofl_addPostFilterAjaxScriptsToFrontend'));
		add_action('wp_ajax_filterlist_action', array($this, 'filterListCallback'));
		add_action('wp_ajax_nopriv_filterlist_action', array($this,'filterListCallback'));
	}//end constructor

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Constants
	 * -----------------------------------------------------------------------------------------------
	 * Plugin constants and paths.
	 * -----------------------------------------------------------------------------------------------
	 */
	function cofl_constants()
	{
		define('FILTERLIST_DIR', plugin_dir_path(__FILE__));
		define('FILTERLIST_FUNCTIONS_DIR',FILTERLIST_DIR . 'functions');
		define('FILTERLIST_VIEWS_DIR',FILTERLIST_DIR . 'views');

		define('FILTERLIST_URL', plugin_dir_url(__FILE__));
		define('FILTERLIST_ASSETS_URL', FILTERLIST_URL . 'assets');
	}
	/**
	 * -----------------------------------------------------------------------------------------------
	 * Plugin includes
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_includes()
	{
		require_once(FILTERLIST_FUNCTIONS_DIR . '/generalFunctions.php');
		require_once(FILTERLIST_FUNCTIONS_DIR .'/BFI_Thumb.php');
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * add shortcode function
	 * -----------------------------------------------------------------------------------------------
	 * This function checks to see if the Visual Composer theme is present. If it is the shortcode is
	 * registered to work with visual composer, if not a plain old Wordpress shortcode is used.
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_addShortcode()
	{
		if(is_plugin_active('js_composer_theme/js_composer.php')) {
			require_once(FILTERLIST_FUNCTIONS_DIR . '/shortcodeHelpers.php');
			add_action('vc_before_init', array($this, 'cofl_registerVisualComposerShortcode'));
		}
		add_action('admin_menu', array($this, 'cofl_pluginMenu'));
		add_shortcode('filter_list', array($this, 'cofl_filterList'));
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Visual composer mapper function
	 * -----------------------------------------------------------------------------------------------
	 */
	function cofl_registerVisualComposerShortcode()
	{
		vc_map(array(
			'name'					=> __('Filter List', 'cohesion'),
			'base'					=> 'filter_list',
			'category' 			=> __('Cohesion', 'cohesion'),
			'params' => array(
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Filter Type', 'cohesion'),
					'param_name' 	=> 'filter_type',
					'value'				=> array('Isotope Filter' => 'isotope', 'Ajax Filter' => 'ajax'),
					'description' => __('Select the Filter type you would like to use', 'cohesion'),
				),
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Post Type', 'cohesion'),
					'param_name' 	=> 'post_type',
					'value'				=> cofl_shortcodeHelpers::cofl_getPostTypes(),
					'description' => __('Select the post type to display in this element', 'cohesion'),
					'group'				=> 'Query',
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Number of entries to show', 'cohesion'),
					'param_name' 	=> 'max',
					'value'				=> '10',
					'description' => __('Select the amount of entries to display', 'cohesion'),
					//'dependency'	=> array('element' => 'filter_type','value' =>'ajax'),
					'group'				=> 'Query',
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Number of entries to offset', 'cohesion'),
					'param_name' 	=> 'offset',
					'value'				=> '',
					'description' => __('Select the amount of entries to offset', 'cohesion'),
					'group'				=> 'Query',
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Categories to include', 'cohesion'),
					'param_name' 	=> 'included_categories',
					'value'				=> '',
					'description' => __('Enter the categories you would like to display, separated with a comma. If left blank all categories will be used', 'cohesion'),
					'group'				=> 'Query',
				),
				array(
					'type'				=> 'textfield',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Categories to exclude', 'cohesion'),
					'param_name' 	=> 'excluded_categories',
					'value'				=> '',
					'description' => __('Enter the categories you do not want to display, separated with a comma', 'cohesion'),
					'group'				=> 'Query',
				),
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Columns', 'cohesion'),
					'param_name' 	=> 'columns',
					'value'				=> array('1' => 1, '2'  => 2, '3' => 3, '4' => 4),
					'description' => __('Select how many items to display per row', 'cohesion'),
					'group'				=> 'Appearance',
				),
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Style', 'cohesion'),
					'param_name' 	=> 'style',
					'value'				=> array('Flat' => 'flat', 'Modern' => 'modern'),
					'description' => __('Select what syle of Filter List you would like to display.', 'cohesion'),
					'group'				=> 'Appearance',
				),
				array(
					'type'				=> 'dropdown',
					'holder' 			=> 'div',
  				'class' 			=> '',
					'heading' 		=> __('Alignment', 'cohesion'),
					'param_name' 	=> 'alignment',
					'value'				=> array('Left' => 'left', 'Bottom' => 'bottom', 'Cover' => 'cover'),
					'description' => __('Select the alignment for this elements animation', 'cohesion'),
					'group'				=> 'Appearance',
					'dependency'	=> array('element' => 'style','value' =>'modern'),
				),
			)
		));
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 *
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_pluginMenu()
	{
		$callback = 'cofl_renderAdminPage';

		if(adminMenuExsits('cohesion')) {
			add_submenu_page(
				'cohesion',
				'Filter List',
				'Filter List',
				'manage_options',
				'cohesion-filter-list',
				 array($this, $callback)
			);
		} else {
			add_plugins_page(
				'Cohesion Filter List',
				'Cohesion Filter List',
				'manage_options',
				'cohesion-filter-list',
				array($this, $callback)
			);
		}
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Menu Page Renderer
	 * -----------------------------------------------------------------------------------------------
	 */
	function cofl_renderAdminPage()
	{
		require_once(FILTERLIST_VIEWS_DIR . '/admin-menu.php');
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Enqueue Scripts and styles
	 * -----------------------------------------------------------------------------------------------
	 * This filter list depends on isotope.js we will use a CDN for this, a falllback does need to be
	 * included though.
	 * @todo 		create fallback.
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_enqeueMedia()
	{
		 wp_enqueue_script('isotope.js', FILTERLIST_ASSETS_URL . '/js/libs/isotope.pkgd.min.js', array('jquery'),'3.0.0', true );
		  wp_enqueue_script('filterList.js', FILTERLIST_ASSETS_URL . '/js/filterList.js', array('jquery'),'1.0.0', true );
		 wp_enqueue_style('filterList.css', FILTERLIST_ASSETS_URL . '/css/filterList.css', '', '', 'all', 18);
		 wp_enqueue_script('fontawesome.js', 'https://use.fontawesome.com/331b945e54.js', array(),'');
	}

	/*
	 * -----------------------------------------------------------------------------------------------
	 * Add Ajax Scripts to Frontend
	 * -----------------------------------------------------------------------------------------------
	 * Add Wordpress's Ajax scripts to the frontend of the site, by default these are only included
	 * in the backend automatically.
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_addPostFilterAjaxScriptsToFrontend()
	{
		$ajax_nonce = wp_create_nonce("filterposts-ajax");
	  $siteUrl = site_url();
	  $html = '<script type="text/javascript">';
	  $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
	  $html .= '</script>';
	  echo $html;
	}

	public function cofl_addPostFilterScriptsToFooter()
	{
		?>
			<script>
				<?php include_once(FILTERLIST_DIR . 'assets/js/min/'.$this->filterType.'-filterlist-min.js'); ?>
			</script>
		<?php
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Shortcode function
	 * -----------------------------------------------------------------------------------------------
	 * This is our shortcode call back function. This is what will be outputted to the frontend.
	 * -----------------------------------------------------------------------------------------------
	 */
	public function cofl_filterList($atts, $content = null)
	{
		extract(cofl_shortcodeHelpers::cofl_extractShortCodeAtts($atts));
		$filterList = cofl_shortcodeHelpers::cofl_getFilterList($included_categories, $post_type);
		$filters = arrayToObject($filterList);
		$this->filterType = $filter_type;
		add_action('wp_footer', array($this, 'cofl_addPostFilterScriptsToFooter'));

		$query = cofl_shortcodeHelpers::cofl_getFilterQuery($atts);
		if($query->have_posts()) {
			?>
				<div class="filter-collection">
					<div class="filter-colloction__inner">
						<?php require_once(FILTERLIST_VIEWS_DIR . '/isotope-filter.php'); ?>
						<div class="js-filter-list filter-list">
							<?php
								while ( $query->have_posts() ) : $query->the_post();
									include(FILTERLIST_VIEWS_DIR . '/item.php');
								endwhile;
							?>
						</div>
						<?php require_once(FILTERLIST_VIEWS_DIR . '/loadmore-button.php'); ?>
					</div>
				</div>
			<?php
		}
	}//end filterList

	public function filterListCallback()
	{
		$posts = array();
		$atts = json_decode(stripslashes($_POST['atts']));
		extract(cofl_shortcodeHelpers::cofl_extractShortCodeAtts($atts));
		$filterList = cofl_shortcodeHelpers::cofl_getFilterList($included_categories, $post_type);
		$filters = arrayToObject($filterList);
		$query = cofl_shortcodeHelpers::cofl_getFilterQuery($atts);
		if($query->have_posts()) {
			while ( $query->have_posts() ) : $query->the_post();
				$categories = cofl_shortcodeHelpers::cofl_getPostTerms(get_the_ID());
				$finalClass = array('style-'.$style, 'column-'.$columns);
				foreach($categories as $category) {
					$finalClass[] = 'filter-'.$category->id;
				}
				$title = get_the_title();
				$link = get_the_permalink($postID);

				switch($style) {
					case 'modern':
						$imageParams = array('width' => 400, 'height' => 400);
						$finalClass[] = 'align-'.$alignment;
					break;
					case 'flat':

					break;
					case 'dsc':
						$imageParams = array('width' => 400, 'height' => 300);
					break;
					default:

					break;
				}

				$image = bfi_thumb($thumbUrl, $imageParams);
				$posts = array(
					'title'  			=> $title,
					'image'				=> $image,
					'link'				=> $link,
					'class'				=> $finalClass,
					'categories'	=> $categories
				);
			endwhile;
		}
		die();
	}
}//end class

/**
 * -------------------------------------------------------------------------------------------------
 * instantiate the class
 * -------------------------------------------------------------------------------------------------
 */
new FilterList();