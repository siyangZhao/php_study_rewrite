jQuery(document).ready(function(){

	//处理页面footer
	var fixedFooterToBottom = function() {
		// console.log(jQuery('body').height());
		// console.log(jQuery(window).height());
	    if (jQuery('body').height() < jQuery(window).height()) {
	    	jQuery('.php-footer').addClass('php-footer-fixed');
	    }
	    else if (jQuery('body').height() > jQuery(window).height()) {
	    	jQuery('.php-footer').toggleClass('php-footer-fixed',false);
	    }
	};

	fixedFooterToBottom();

	jQuery(window).resize(function(event) {
		fixedFooterToBottom();
	});
});