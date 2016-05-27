<?php
/**
 *
 */
$params = array('width' => 400, 'height' => 400);
$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, '', true);
$thumb_url = $thumb_url_array[0];
$image = bfi_thumb($thumb_url, $params);
$title = get_the_title(get_the_ID());
$categories = cofl_shortcodeHelpers::cofl_getPostTerms(get_the_ID());
?>

<div class="filter-list--item column-<?php echo $columns; ?> style-<?php echo $style; ?>">
	<div class="filter-list--item--image">
		<?php if(has_post_thumbnail(get_the_ID())) { ?>
			<img src="<?php echo $image; ?>" />
		<?php } else { ?>
			<img src="http://placehold.it/400x400">
		<?php } ?>
	</div>
	<div class="filter-list--item--content">
		<h3><?php echo $title; ?></h3>
		<ul class="inline-list">
			<?php
				foreach($categories as $category) {
					if(array_key_exists($category->name, $filterList)) {//in_array('egg', $array, true)
						echo '<li claass="inline-list--item">'.$category->name . '</li>';
					}
				}
			?>
		</ul>

		<a class="post-link" href="<?php echo get_the_permalink(get_the_ID()); ?>">Read More..</a>
	</div>
</div>