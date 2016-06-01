<?php
/**
 * -------------------------------------------------------------------------------------------------
 * Load More Button Script
 * -------------------------------------------------------------------------------------------------
 */
$atts['offset'] += $max;
//var_dump(json_encode($atts));
?>
	<div class="filter-list-loadmore">
		<div class="filter-list-loadmore__inner">
			<a class="js-filter-list-loadmore" href="#" data-filter-list-atts='<?php echo json_encode($atts); ?>'>Load More</a>
		</div>
	</div>