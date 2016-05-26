<?php
/**
 * -------------------------------------------------------------------------------------------------
 * Isotope filter template
 * -------------------------------------------------------------------------------------------------
 */
?>

<div class="isotope-filter">
	<div class="isotope-filter__inner">
		<ul class="isotope-filter__list"><?php?>
			<li class="isotope-filter__list--item">
				<button  class="isotope-filter--button" data-filter=".filter-all">show all</button>
			</li>
			<?php foreach($filters as $filter) { ?>
				<?php ?>
					<li class="isotope-filter__list--item">
					<button class="isotope-filter--button" data-filter=".filter-<?php echo $filter->id; ?>">
						<?php echo $filter->name; ?>
						</button>
					</li>
				<?php ?>
			<?php	} ?>
		</ul>
	</div>
</div>