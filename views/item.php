<?php
/**
 * -------------------------------------------------------------------------------------------------
 *
 * -------------------------------------------------------------------------------------------------
 */
$postID = get_the_ID();
$finalClass = cofl_shortcodeHelpers::cofl_getfinalClass($postID, $atts);
$categories = cofl_shortcodeHelpers::cofl_getPostTerms(get_the_ID());
$title = get_the_title();
$link = get_the_permalink($postID);
?>

<div class="filter-list--item <?php echo implode(' ', $finalClass); ?>">
	<div class="filter-list--item--image">
		<?php if(has_post_thumbnail(get_the_ID())) { ?>
			<?php $image = cofl_shortcodeHelpers::cofl_getPostImage($postID, $style); ?>
			<img src="<?php echo $image; ?>" />
		<?php } else { ?>
			<img src="http://placehold.it/400x400">
		<?php } ?>
	</div>
	<div class="filter-list--item--content">
		<h3><?php echo $title; ?></h3>
		<ul class="category-list">
			<?php
				foreach($categories as $category) {
					if(array_key_exists($category->name, $filterList)) {
						echo '<li class="category-list--item">'.$category->name . '</li>';
					}
				}
			?>
		</ul>
		<a class="post-link" href="<?php echo $link; ?>">Read More..</a>
	</div>
</div>