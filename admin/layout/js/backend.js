$(function(){
    $('[placeholder]').focus(function(){
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    $('input').each(function () {
       if($(this).attr('required')==='required'){
           $(this).after('<span class="asterisk">*</span>');
       }
    });

    var password = $('.password');
    $('.show-pass').hover(function () {
       password.attr('type','text');
    },function () {
        password.attr('type','password');
    });

    $('.confirm').click(function () {
        return confirm('Are You Sure ?');
    });
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(200);
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-plus"></i>');
        }else{
            $(this).html('<i class="fa fa-minus"></i>');
        }
    });

});