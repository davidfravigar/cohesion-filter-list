!function($){$(document).ready(function(){$(document).find(".filter-collection").each(function(){function t(t){try{return t=JSON.parse(t),!0}catch(i){return!1}}function i(t){var i=jQuery.parseJSON(t),e=i.offset;if(void 0===i.max||""===i.max)i.offset=e+10;else{var s=i.max;i.offset=e+parseInt(s)}var a=JSON.stringify(i);return a}function e(t){var i="",e=JSON.parse(t);$.each(e,function(){i+='<div class="filter-list--item '+this["class"]+'">',i+='<div class="filter-list--item--image">',i+='<img src="'+this.image+'" />',i+="</div>",i+='<div class="filter-list--item--content">',i+="<h3>"+this.title+"</h3>",i+='<ul class="category-list">',$.each(this.categories,function(){i+='<li class="category-list--item">',i+=this.name,i+="<li>"}),i+="</ul>",i+='<a class="post-link" href="'+this.link+'">Read more...</a>',i+="</div>",i+="</div>"});var a=$($.parseHTML(i));s.append(a).isotope("appended",a).isotope(),s.isotope("layout")}var s=$(this).find(".js-filter-list");s.isotope({itemSelector:".filter-list--item",layoutMode:"fitRows",percentPosition:!0,fitRows:{gutter:10}}),$(this).find(".isotope-filter--button").on("click",function(){var t=$(this).attr("data-filter");s.isotope({filter:t})}),$(this).find(".js-filter-list-loadmore").on("click",function(s){s.preventDefault();var a=$(this);a.find(".text").text("loading..."),a.find(".is-loading").css("display","inline-block");var o=$(this).attr("data-filter-list-atts"),n={action:"filterlist_action",security:"<?php echo $ajax_nonce; ?>",atts:o};$.post(ajaxurl,n,function(s){if(t(s)){e(s,o);var n=i(o);a.attr("data-filter-list-atts",n)}}).fail(function(){console.log("error")}).success(function(){a.find(".text").text("Load More"),a.find(".is-loading").css("display","none")})})})})}(jQuery);