<?php

class cofl_shortcodeHelpers
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

	/**
	 * -----------------------------------------------------------------------------------------------
	 * [fetchPosts description]
	 * -----------------------------------------------------------------------------------------------
	 * @param  string  $posttype   [description]
	 * @param  integer $limit      [description]
	 * @param  array   $passedArgs [description]
	 * @return [type]              [description]
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_fetchPosts($posttype='posts', $limit=-1, $passedArgs = array())
	{
		global $wp_query;
		$args = array_merge(array(
			'post_type' 			=> $posttype,
			'post_status'			=> 'publish',
			'posts_per_page'	=> $limit,
		), $passedArgs);

		$query = new WP_Query($args);
		wp_reset_postdata();
		wp_reset_query();
		return $query;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 *
	 * -----------------------------------------------------------------------------------------------
	 * @param  [type] $type     [description]
	 * @param  [type] $postType [description]
	 * @return [type]           [description]
	 * -----------------------------------------------------------------------------------------------
	 */
	private static function getPostTypeTerms($postType='post', $fields='all')
	{
		$query = self::cofl_fetchPosts($postType);
		$terms = array();
		if($query->have_posts()) {
			while ( $query->have_posts() ) : $query->the_post();
       	$postTerms = wp_get_object_terms(get_the_ID(), 'category');
       	foreach ($postTerms as $term){
          //avoid duplicates
          if (!in_array($term->name,$terms)){
          		$termInfo = array(
								'id'			=> $term->term_id,
								'name'		=> $term->name,
							);
              $terms[$term->name] = $termInfo;
          }
        }
     	endwhile;
		}

		return $terms;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 *
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_getFilterList($incTerms='', $postType='post')
	{
		$termsList = array();
		if(!empty($incTerms)) {
			$terms = self::cofl_getTermIds($incTerms);
			foreach($terms as $term) {
				$termObject = get_term($term, 'category');
					$termInfo = array(
						'id'			=> $termObject->term_id,
						'name'		=> $termObject->name,
					);
				$termsList[$termObject->name] = $termInfo;
			}
		} else {
			$termsList = self::getPostTypeTerms($postType);
		}
		return $termsList;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Extract atts function
	 * -----------------------------------------------------------------------------------------------
	 * Extract shortcode atts.
	 * @param  array $atts
	 * @return array
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_extractShortCodeAtts($atts)
	{
		return shortcode_atts(array(
			'post_type'							=> 'post',
			'max'										=> '10',
			'offset'								=> '',
			'included_categories'		=> '',
			'excluded_categories'		=> '',
			'columns'								=> '3',
			'style'									=> 'flat'
		), $atts);
	}

	public static function cofl_getPostTerms($id)
	{
		$categories = wp_get_post_categories($id);
		$cats = array();
		foreach($categories as $category){
		    $cat = get_category($category);
		    $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
		}

		return arrayToObject($cats);
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Get a terms id.
	 * -----------------------------------------------------------------------------------------------
	 * @param  array|string $terms  can either have a string or array passed in of term names
	 * @return array 	term ids
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_getTermIds($terms)
	{
		if(!is_array($terms)) {
			$termsList = explode(',', $terms);
		}
		$termsToReturn = self::cofl_convertTermSlugsToIds($termsList);
		return $termsToReturn;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * convertTermSlugsToIds
	 * -----------------------------------------------------------------------------------------------
	 * Convert Term Slugs to IDs.
	 * @param  array $terms the term names to find
	 * @return array $ids the ids of the terms passed in
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_convertTermSlugsToIds($terms)
	{
		$ids = array();

		foreach($terms as $term) {
			if(is_numeric($term)) {
				$ids[] = $term;
			} else {
				$id = self::cofl_convertTermSlugToId($term);
				if($id !== false) {
					$ids[] = $id;
				}
			}
		}
		return $ids;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Convert term Slugs to ids
	 * -----------------------------------------------------------------------------------------------
	 * @param  string $slug
	 * @return int term_id
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_convertTermSlugToId($slug)
	{
		$term = get_term_by('slug', $slug, 'category');
		if($term && !is_wp_error($term)) {
			return $term->term_id;
		}

		$term = get_term_by('slug', $slug, 'post_tag');
		if($term && is_wp_error($term)) {
			return $term->term_id;
		}
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Create our base query
	 * -----------------------------------------------------------------------------------------------
	 * @param  integer $offset   	[description]
	 * @param  integer $max      	[description]
	 * @param  string  $postType 	[description]
	 * @return array           		[description]
	 * -----------------------------------------------------------------------------------------------
	 */
	private static function cofl_createBaseQuery($offset=0, $max=10, $postType='post')
	{
		$queryArgs = array(
			'post_type'								=> $postType,
			'posts_per_page'					=> $max,
			'orderby'									=> 'date',
			'order'										=> 'desc',
			'ignore_sticky_posts'			=> true,
			'post_status'							=> 'publish',
		);

		$offset = absint($offset);
		if ($offset > 0) {
	    $queryArgs['offset'] = $offset;
	  }
	  return $queryArgs;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * [cofl_createTaxQuery description]
	 * -----------------------------------------------------------------------------------------------
	 * @param  [type] $includedTerms [description]
	 * @param  [type] $excludedTerms [description]
	 * @return [type]                [description]
	 * -----------------------------------------------------------------------------------------------
	 */
	private static function cofl_createTaxQuery($includedTerms, $excludedTerms)
	{
		$taxQuery = array('relation' => 'AND');
		if(!empty($includedTerms)) {
			$incTerms = self::cofl_getTermIds($includedTerms);
			$taxQuery[] = array(
				'taxonomy'				=> 'category',
				'field'						=> 'id',
				'terms'						=> $incTerms,
				'operator'				=> 'IN',
			);
		}

		if(!empty($excludedTerms)) {
			$exclTerms = self::getTermIds($excludedTerms);
			$taxQuery[] = array(
				'taxonomy'				=> 'category',
				'field'						=> 'id',
				'terms'						=> $exclTerms,
				'operator'				=> 'NOT_IN'
			);
		}

		return $taxQuery;
	}

	/**
	 * -----------------------------------------------------------------------------------------------
	 * Build Filter Query function
	 * -----------------------------------------------------------------------------------------------
	 * Build and return our db query.
	 * @param  array $atts 		Our shortcode atts
	 * @return array $query 	The return WP_query object.
	 * -----------------------------------------------------------------------------------------------
	 */
	public static function cofl_getFilterQuery($atts)
	{
		extract(self::cofl_extractShortCodeAtts($atts));
		$queryArgs = self::cofl_createBaseQuery($offset, $max, $post_type);
		$queryArgs['tax_query'] = self::cofl_createTaxQuery($included_categories, $excluded_categories);
		$query = new WP_Query($queryArgs);
		wp_reset_query();
		return $query;
	}
}