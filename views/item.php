<?php
$params = array('width' => 400, 'height' => 300);
$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, '', true);
$thumb_url = $thumb_url_array[0];
$image = bfi_thumb($thumb_url, $params);
?>
<div class="filter-list--item column-<?php echo $columns; ?> style-<?php echo $style; ?>">
	<div class="filter-list--item--image">
		<?php if(has_post_thumbnail(get_the_ID())) { ?>
			<img src="<?php echo $image; ?>" />
		<?php } else { ?>
			<img src="http://placehold.it/400x300">
		<?php } ?>
	</div>
	<div class="filter-list--item--title">
		<h3><?php echo get_the_title(get_the_ID()); ?></h3>
	</div>
	<div class="filter-list--item--description">
		<p><?php //echo get_the_excerpt(get_the_ID()); ?></p>
	</div>
</div>