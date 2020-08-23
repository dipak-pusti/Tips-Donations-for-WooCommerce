(function( $ ) {
	'use strict';

	$(function() {

		// AJAX Add / Remove Tips
		$(document).on('click', '.tips-action', function(event) {
			event.preventDefault();

			$('.tips-spinner').addClass('active');
			$('.tips-error').removeClass('active');

			var action = $(this).attr( 'data-action' );

			if( action == 'add_tips' ) {

				var ele = $(this).parent();
	            var amt = ele.find('.tips-amount').val();
	            var nce = otd_vars.add_tips_nonce;

	            if ( isNaN(amt) === true) {
	            	$(".tips-spinner").removeClass('active');
	                $(".tips-error").html(otd_vars.invalid_tip_text).addClass('active');
	                return false;
	            }
			}

			if( action == 'remove_tips' ) {
				var amt = 0;
				var nce = otd_vars.del_tips_nonce;
			}

            var data = {
            	action      : action,
            	amount  	: amt,
            	security    : nce,
            };

            $.ajax({
        		type	: "POST",
        		data	: data,
        		url		: otd_vars.ajaxurl,
        		success	: function( response ) {
        			
        			// Trigger Cart Update
        			if ($("[name='update_cart']").length > 0) {
                        $("[name='update_cart']").prop("disabled", false);
        				$("[name='update_cart']").trigger("click"); 
                    }
                    
                    // Trigger Checkout Page Update
        			$('body').trigger('update_checkout');

        			$(".tips-spinner").removeClass('active');
        			return false;
        		}
        	});
		});
	});

})( jQuery );