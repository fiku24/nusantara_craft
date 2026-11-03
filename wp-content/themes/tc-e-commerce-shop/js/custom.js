jQuery(function($){
	"use strict";
	jQuery('.main-menu-navigation > ul').superfish({
		delay:       500,                            
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'                        
	});
});

function tc_e_commerce_shop_menu_open() {
	jQuery(".side-menu").addClass('open');
}
function tc_e_commerce_shop_menu_close() {
	jQuery(".side-menu").removeClass('open');
}

(function( $ ) {

	$(window).scroll(function(){
	  	var sticky = $('.sticky-header'),
      	scroll = $(window).scrollTop();

	 	if (scroll >= 100) sticky.addClass('fixed-header');
	  	else sticky.removeClass('fixed-header');
	});

	// Back to top
	jQuery(document).ready(function () {
	    jQuery(window).scroll(function () {
      		if (jQuery(this).scrollTop() > 0) {
	          	jQuery('.scrollup').fadeIn();
	      	} else {
	          	jQuery('.scrollup').fadeOut();
	      	}
	    });
	    jQuery('.scrollup').click(function () {
	      	jQuery("html, body").animate({
	          	scrollTop: 0
	      	}, 600);
	      	return false;
	    });
	});

	// Window load function
	window.addEventListener('load', (event) => {
		$(".preloader").delay(2000).fadeOut("slow");
	});

})( jQuery );

( function( window, document ) {
	function tc_e_commerce_shop_keepFocusInMenu() {
		document.addEventListener( 'keydown', function( e ) {
			const tc_e_commerce_shop_nav = document.querySelector( '.side-menu' );

			if ( ! tc_e_commerce_shop_nav || ! tc_e_commerce_shop_nav.classList.contains( 'open' ) ) {
				return;
			}

			const elements = [...tc_e_commerce_shop_nav.querySelectorAll( 'input, a, button' )],
				tc_e_commerce_shop_lastEl = elements[ elements.length - 1 ],
				tc_e_commerce_shop_firstEl = elements[0],
				tc_e_commerce_shop_activeEl = document.activeElement,
				tabKey = e.keyCode === 9,
				shiftKey = e.shiftKey;

			if ( ! shiftKey && tabKey && tc_e_commerce_shop_lastEl === tc_e_commerce_shop_activeEl ) {
				e.preventDefault();
				tc_e_commerce_shop_firstEl.focus();
			}

			if ( shiftKey && tabKey && tc_e_commerce_shop_firstEl === tc_e_commerce_shop_activeEl ) {
				e.preventDefault();
				tc_e_commerce_shop_lastEl.focus();
			}
		} );
	}

	tc_e_commerce_shop_keepFocusInMenu();
} )( window, document );

/*sticky copyright*/
window.addEventListener('scroll', function() {
  var tc_e_commerce_sticky = document.querySelector('.copyright-sticky');
  if (!tc_e_commerce_sticky) return;

  var scrollTop = window.scrollY || document.documentElement.scrollTop;
  var windowHeight = window.innerHeight;
  var documentHeight = document.documentElement.scrollHeight;

  var isBottom = scrollTop + windowHeight >= documentHeight-100;

  if (scrollTop >= 100 && !isBottom) {
    tc_e_commerce_sticky.classList.add('copyright-fixed');
  } else {
    tc_e_commerce_sticky.classList.remove('copyright-fixed');
  }
});
// Custom Cursor JS Load Control
document.addEventListener("mousemove", function(e){
    let cursor = document.querySelector(".custom-cursor");
    if(cursor){
        cursor.style.left = e.clientX + "px";
        cursor.style.top = e.clientY + "px";
    }
});
