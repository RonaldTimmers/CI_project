function expandDescription() {
        $(document).ready(function(){
        var $minHeight = 200;

            if ($('.product-descrp').height() > $minHeight){
                    $('.product-descrp').css('height', '200px');
                    $('.more-arrow').css('background', 'url("../../img/icons/expand_arrow_open.png") 0 0 no-repeat');   
                    $('.descr-more').css('display', 'inline-block'); 
                   
                $(".descr-more").click(function(){ 
                            var $elem = $(this).parent();
                            if($elem.hasClass("short"))
                            {
                                    $elem.removeClass("short").addClass("full");
                                    $('.more-arrow').css('background', 'url("../../img/icons/expand_arrow_close.png") 0 0 no-repeat');
                                    $('.product-descrp').css('height', 'auto');
                            }
                            else
                            {
                                    $elem.removeClass("full").addClass("short");
                                    $('.more-arrow').css('background', 'url("../../img/icons/expand_arrow_open.png") 0 0 no-repeat');
                                    $('.product-descrp').css('height', '200px');
                            }       
                    });
            }  
       });
}


function gotoTop() {

	$(window).scroll(function() {
		if ($(this).scrollTop())
		 {
		    $('#goTopButton').removeClass("hidden");
		    $('#goTopButton').fadeIn();
		 }
		 
		else
		 {
		    $('#goTopButton').fadeOut().addClass("hidden");
		 }
	});     
}


function isScrolledIntoView(elem)
	{	
		if (typeof(elem) != "undefined") {
		    var docViewTop = $(window).scrollTop();
		    var docViewBottom = docViewTop + $(window).height();

		    var elemTop = $(elem).offset().top;
		    var elemBottom = elemTop + $(elem).height();

		    return ((elemTop >= docViewTop)); //(elemBottom <= docViewBottom) && 
		}

	}


function fixedProductButton () {
	$(window).scroll(function() {

		if (isScrolledIntoView('#product-buy')) {
			$('#product-button-container-fixed').fadeOut().addClass("hidden");	
		} else {
		  	
		   	$('#product-button-container-fixed').removeClass("hidden");
	    	$('#product-button-container-fixed').fadeIn();
		}

	});
}


function scrollToTop() {
	var timeOut;
	if (document.body.scrollTop !=0 || document.documentElement.scrollTop!=0){
		window.scrollBy(0,-100);
		timeOut=setTimeout('scrollToTop()',10);
	}
	else {
		clearTimeout(timeOut);
	}
}

function scrollToTopProducts() {
    $('html, body').animate({
        scrollTop: $("#scroll-to-element").offset().top + (-80)
    }, 500);
}


function get_JSnavbar(baseURL) {
    $(document).ready(function(){
            
            //var JSnavbar;
            
            
            $.ajax({
              url: baseURL +"includes/html/main_cat_navigation.html",
              cache: false,
              success: function(response){
              	//JSnavbar = response;
              	$('ul#navigation-main').html(response); 
              }
            });
            
            /*
            $('#navigation-header').on( "mouseenter", function () {
				console.log("Hover Test");
				$('#nav-wrapper').toggleClass("hidden");
				$('#cats-open-close').toggleClass("glyphicon-menu-down glyphicon-menu-up");	
			});

            $('#navBarTop').on( "mouseleave", function () {
				console.log("Hover Out");
				$('#nav-wrapper').toggleClass("hidden");
				$('#cats-open-close').toggleClass("glyphicon-menu-down glyphicon-menu-up");	
			});
			*/
            
    });     
}

function toggleMenu() {

  	//$('#nav-wrapper').toggleClass("hidden");
		
    $('#mobile-nav').toggleClass("hidden");
    scrollToTop();

    //$('#menu-open-close').toggleClass("glyphicon-menu-down glyphicon-menu-up");

}

$(document).on('click', function(event) {
  if (!$(event.target).closest('#mobileNav-wrapper').length && !$(event.target).closest('#hamburger-icon').length ) {
    // Hide the menus.
	$('#mobileNav-wrapper').addClass("hidden");
	$('#menu-open-close').removeClass( "glyphicon glyphicon-menu-up" ).addClass("glyphicon glyphicon-menu-down");
  }
});


var count = 1;
var start = 0;

function ajax_call( scrollElement, count, start ) {
  //console.log(window.product_ids);
  var offset = start + 12;
  var counter = count;
  
  //console.log(counter);
  //console.log('offset: ' + offset);
	const relatedProductsHTML = window.baseURL + "src/relatedProductsHTML.call.php";

    if ( counter <= 3 ) {
      
 
        $.ajax({
            type: "POST",
            url: relatedProductsHTML,
            dataType: "html",
            data: { 
                    action: "load_more", 
                    main_product_id: window.main_product_id,
                    main_product_source: window.main_product_source, 
                    main_product_title: window.main_product_title, 
                    main_product_price: window.main_product_price, 
                    offset: offset
                  },
            success: function(data) {
                
                $('.loading-related').remove();
                $('.load-more-divider').before(data);
                window.lazy();
                thumbImageFunction(); 

                $(scrollElement).on('scroll.related', function() {

                    var elementOffset =  $('.modal-relatedProducts').offset().top ;
                    var elementHeight =  $('.modal-relatedProducts').innerHeight();
                    //console.log( elementOffset + ( elementHeight - 600  ) );
                    //console.log( $(scrollElement).scrollTop() );


                    if( elementOffset + ( elementHeight ) <=  $(scrollElement).scrollTop()) {
               
                        $('.load-more-divider').before('<img class="loading-related center-block" src="/img/spinner.gif">');
                        
                        $(scrollElement).off('scroll.related');
                        ajax_call( scrollElement, counter, offset);
                    }

                });
                
                counter++;
            },
            error: function(data) {
            //console.log( "Error Occured");
                console.log("data", data);
            }
        }); 
    } else {
        $('.loading-related').remove();

    }
  
};


function thumbImageFunction () {
      $('.thumb').on({
        'mouseenter': function(){
            var product = $(this).offsetParent();
            var newImage = $(this).attr('src');
            
            product.find("img.product-thumb").attr('src', newImage);
        }   
    });
};

   