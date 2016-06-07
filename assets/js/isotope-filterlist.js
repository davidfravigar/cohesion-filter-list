(function($) {
	$(document).ready(function() {
		$(document).find('.filter-collection').each(function() {
			var $filterList = $(this).find('.js-filter-list');
			$filterList.isotope({
				itemSelector: '.filter-list--item',
		 	  layoutMode: 'fitRows',
		 	  percentPosition: true,
		 	  fitRows: {
				  gutter: 10
				}
			});

			/**
	     * -------------------------------------------------------------------
	     * Parse Results
	     * -------------------------------------------------------------------
	     * This is our true of false function for when we have a response.
	     * @param 		string data the response from the ajax call
	     * @return 		bool
	     * -------------------------------------------------------------------
	     */
	    function parseResults(data) {
	      try{
	        data = JSON.parse(data);
	        return true;
	      } catch(e) {
	        return false;
	      }
	    }

	    function addOffsetToLoadMoreButton(atts) {
	    	var oldAtts = jQuery.parseJSON(atts);
	    	var offset = oldAtts.offset;
	    	if(oldAtts.max === undefined || oldAtts.max === '') {
	    		oldAtts.offset = offset+10;
	    	} else {
	    		var max = oldAtts.max;
	    		oldAtts.offset = offset + parseInt(max);
	    	}
	    	var newAtts = JSON.stringify(oldAtts);
	    	return newAtts;
	    }

	    /**
	     * Add Items to post filter list.
	     * TODO clone and append the element.
	     */
	    function addItemsToFilterList(data) {
	    	var html = '';
	    	var items = JSON.parse(data);
	    	$.each(items, function() {
	    		html += '<div class="filter-list--item ' + this.class + '">';
	    		html += '<div class="filter-list--item--image">';
	    		html += '<img src="'+this.image+'" />';
	    		html += '</div>';
	    		html += '<div class="filter-list--item--content">';
	    		html += '<h3>' + this.title + '</h3>';
	    		html += '<ul class="category-list">';
	    		$.each(this.categories, function() {
	    			html += '<li class="category-list--item">';
	    			html += this.name;
	    			html += '<li>';
	    		});
	    		html += '</ul>';
	    		html += '<a class="post-link" href="'+ this.link +'">Read more...</a>';
	    		html += '</div>';
	    		html +='</div>';
	    	});
	    	var $listItems = $($.parseHTML(html));
	    	$filterList.append($listItems).isotope('appended', $listItems).isotope();
	    	$filterList.isotope('layout');
	    }

			$(this).find('.isotope-filter--button').on('click', function() {
				var filterValue = $(this).attr('data-filter');
				$filterList.isotope({filter: filterValue });
			});

			$(this).find('.js-filter-list-loadmore').on('click', function(event) {
				event.preventDefault();
				var loadmore = $(this);
				loadmore.find('.text').text('loading...');
				loadmore.find('.is-loading').css('display', 'inline-block');
				var atts = $(this).attr('data-filter-list-atts');
				var data = {
    			'action': 'filterlist_action',
          'security': '<?php echo $ajax_nonce; ?>',
          'atts': atts,
    		};
    		$.post(ajaxurl, data, function(response){
    	 		if(parseResults(response)) {
    	 			addItemsToFilterList(response, atts);
    	 			var newAtts = addOffsetToLoadMoreButton(atts);
    	 			loadmore.attr('data-filter-list-atts', newAtts);
    	 		}
        })
        .fail(function(){
          console.log('error');
        })
        .success(function(){
        	loadmore.find('.text').text('Load More');
        	loadmore.find('.is-loading').css('display', 'none');
        });
			});
		});
	});
})(jQuery);