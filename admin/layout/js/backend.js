

$(function () {
   
    'use strict';

    // Dashboard 

    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').next('.panel-body').fadeToggle(100);

        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg"></i>');   
        }
    });




    //Trigger SElect Box plugin at item page
    
    $("select").selectBoxIt({
        autoWidth: false
    });


    // Hide placeholder on form focus
    $('[placeholder]').focus(function () {
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
        
    });

    // Add * for required Feilds
    $('input').each(function () {

    	if ($(this).attr('required') === 'required') {

    		$(this).after('<span class="asterisk">*</span>');
 	   }
    });
    
    // Convert password feild to text field on hover
    var passfield = $('.password');
    $('.show-pass').hover(function () {
        
        passfield.attr('type', 'text');
        
    }, function () {
        
        passfield.attr('type', 'password');
        
    });

    // Confirmation Message To DElete Member

    $('.confirm').click(function () {

        return confirm('Are You Sure ?');
    });

    // Category View Option

    $('.cat h3').click(function() {

        $(this).next('.full-view').fadeToggle(500);

    });

    $('.option span').click(function () {

        $(this).addClass('active').siblings('span').removeClass('active'); // siblings('span') -> Without Span will remove from ASc and DESC i need to remove from span only

        if ($(this).data('view') == 'classic') {

            $('.cat .full-view').fadeOut(200);
        } else {

            $('.cat .full-view').fadeIn(200);
        }
    });


    // Show Delete Button on child Categoies
    $('.child-link').hover(function () {

        $(this).find('.show-delete').fadeIn(300);

    },function () {

        $(this).find('.show-delete').fadeOut();
    });
    
});