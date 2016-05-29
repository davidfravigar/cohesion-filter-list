<?php
/**
 * -------------------------------------------------------------------------------------------------
 *
 * -------------------------------------------------------------------------------------------------
 */
$postID = get_the_ID();
$thumbID = get_post_thumbnail_id();
$thumbUrlArray = wp_get_attachment_image_src($thumbID, '', true);
$thumbUrl = $thumbUrlArray[0];

$finalClass = array('style-'.$style, 'column-'.$columns);
$categories = cofl_shortcodeHelpers::cofl_getPostTerms(get_the_ID());
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
?>

<div class="filter-list--item <?php echo implode(' ', $finalClass); ?>">
	<div class="filter-list--item--image">
		<?php if(has_post_thumbnail(get_the_ID())) { ?>
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