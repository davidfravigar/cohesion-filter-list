!function($){$(document).ready(function(){$(document).find(".filter-collection").each(function(){var t=$(this).find(".js-filter-list");t.isotope({itemSelector:".filter-list--item",layoutMode:"fitRows"}),$(this).find(".isotope-filter--button").on("click",function(){console.log("filter clicked");var o=$(this).attr("data-filter");console.log(o),t.isotope({filter:o})}),$(this).find(".js-filter-list-loadmore").on("click",function(t){t.preventDefault(),console.log("ajax url: "+ajaxurl);var o=$(this).attr("data-filter-list-atts"),i={action:"filterlist_action",security:"<?php echo $ajax_nonce; ?>",atts:o};$.post(ajaxurl,i,function(t){console.log(t)}).fail(function(){console.log("error")})})})})}(jQuery);