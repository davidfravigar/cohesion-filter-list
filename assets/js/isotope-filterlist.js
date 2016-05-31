(function($) {
	$(document).ready(function() {
		$(document).find('.filter-collection').each(function() {
			var $filterList = $(this).find('.js-filter-list');
			$filterList.isotope({
				itemSelector: '.filter-list--item',
		 	  layoutMode: 'fitRows'
			});

			$(this).find('.isotope-filter--button').on('click', function() {
				console.log('filter clicked');
				var filterValue = $(this).attr('data-filter');
				console.log(filterValue);
				$filterList.isotope({filter: filterValue });
			});

			$(this).find('.js-filter-list-loadmore').on('click', function(event) {
				event.preventDefault();
				console.log('add more clicked with atts: ');
				var atts = $(this).attr('data-filter-list-atts');
				console.log(atts);
			});
		});
	});
})(jQuery);