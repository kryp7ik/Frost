$(document).ready(function() {
    $.material.init();
    $.material.ripples();
    $('select').select2();

    $('.password').keyup(function() {
        $('.confirm-password').fadeIn();
    });

});

$('div.alert').not('.alert-important').delay(3000).fadeOut(250);

