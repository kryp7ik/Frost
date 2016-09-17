$(document).ready(function() {
    $.material.init();
    $.material.ripples();
    $('select').select2();

});

$('div.alert').not('.alert-important').delay(3000).fadeOut(250);