

$(document).ready(function() {
    $.fn.extend({
        animateCss: function (animationName) {
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            this.addClass('animated ' + animationName).one(animationEnd, function() {
                $(this).removeClass('animated ' + animationName);
            });
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
    $.material.init();
    $.material.ripples();
    $('select').select2();

    $('.password').keyup(function() {
        $('.confirm-password').fadeIn();
    });

    $('#datepicker').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('#order-id').keyup(function() {
        var orderId = this.value;
        $('#lookup').attr('href', '/orders/' + orderId + '/show');
    });

    $('#email-receipt').on('click', function() {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {
            $this.button('reset');
        }, 4000);
        $.post('/orders/email-receipt',{ 'order' : $(this).attr('data-order') }, function(data) {
            if(data.status == 'success') {
                $('body').append('<div class="alert alert-success">The e-mail was sent successfully</div>');
            } else {
                $('body').append('<div class="alert alert-danger">An error occurred while sending the e-mail please ensure the customers e-mail is correct</div>');
            }
        });
    });

});

$(document).on('click', '.liquid-add', function(e)
{
    e.preventDefault();
    var controlForm = $('.liquid-group'),
        currentEntry = $(this).parents('.liquid-group fieldset:first');
    $("select").select2("destroy");
    var newEntry = $(currentEntry.clone()).appendTo(controlForm);
    var currentCount = ($('.liquid-group fieldset').length -1);
    newEntry.find('.l-size').attr('name', 'liquids[' + currentCount + '][size]');
    newEntry.find('.l-select').attr('name', 'liquids[' + currentCount + '][recipe]');
    newEntry.find('.l-nicotine').attr('name', 'liquids[' + currentCount + '][nicotine]');
    newEntry.find('.l-nicotine').val(currentEntry.find('.l-nicotine').val());
    newEntry.find('.l-extra').attr('name', 'liquids[' + currentCount + '][extra]');
    newEntry.find('.l-menthol').attr('name', 'liquids[' + currentCount + '][menthol]');
    newEntry.find('.l-menthol').val(currentEntry.find('.l-menthol').val());
    newEntry.find('.l-vg').attr('name', 'liquids[' + currentCount + '][vg]');
    newEntry.find('.l-vg').val(currentEntry.find('.l-vg').val());
    controlForm.find('fieldset:not(:last) .liquid-add')
        .removeClass('liquid-add').addClass('liquid-remove')
        .removeClass('btn-success').addClass('btn-danger')
        .html('<span class="glyphicon glyphicon-minus"></span>');
    $("select").select2();
}).on('click', '.product-add', function(e)
{
    e.preventDefault();
    var controlForm = $('.product-group'),
        currentEntry = $(this).parents('.product-group fieldset:first');
    $("select").select2("destroy");
    var newEntry = $(currentEntry.clone()).appendTo(controlForm);
    var currentCount = ($('.product-group fieldset').length -1);
    newEntry.find('.p-select').attr('name', 'products[' + currentCount + '][instance]');
    newEntry.find('.p-quantity').attr('name', 'products[' + currentCount + '][quantity]');
    newEntry.find('.p-quantity').val('');
    controlForm.find('fieldset:not(:last) .product-add')
        .removeClass('product-add').addClass('product-remove')
        .removeClass('btn-success').addClass('btn-danger')
        .html('<span class="glyphicon glyphicon-minus"></span>');
    $("select").select2();
}).on('click', '.product-remove', function(e)
{
    $(this).parents('fieldset:first').remove();
    e.preventDefault();
    return false;
}).on('click', '.liquid-remove', function(e)
{
    $(this).parents('fieldset:first').remove();
    e.preventDefault();
    return false;
});
$('#customer-phone').on('click', function() {
    $('#customer-phone').animateCss('hinge');
    $('#change-customer').fadeIn();
    setTimeout(function() {
        $('#customer-phone').fadeOut();
    }, 2000);
    $('#phone').focus();
});
$('#cancel-phone').on('click', function(e) {
    e.preventDefault();
    $('#customer-phone').fadeIn();
    $('#change-customer').fadeOut();
});
$('#cash').on('shown.bs.modal', function () {
    $('#amount').focus();
});

$('#preferred').on('click', function() {
    var button = $(this);
    var value = ($(this).attr('data-pref') == 0) ? 1 : 0;
    $.ajax({
        url: '/customers/' + $(this).attr('data-id') + '/ajax',
        type: "POST",
        dataType: "json",
        data: {
            'name' : 'preferred',
            'value' : value
        }
    }).done(function( json ) {
        if(json.status == 1) {
            if (value == 0) {
                $(button).attr('data-pref', 0);
                $(button).html('No');
                $(button).removeClass('btn-success');
                $(button).addClass('btn-danger');
            } else {
                $(button).attr('data-pref', 1);
                $(button).html('Yes');
                $(button).removeClass('btn-danger');
                $(button).addClass('btn-success');
            }
        }
    });
});

$(document).on('click', '#confirm-transfer', function() {
    $('#transfer-form').submit();
});

// Gathers form data and compiles into a table for user confirmation
$('#modal').on('show.bs.modal', function () {
    var modal = $(this);
    $('#from').append($('#from-select :selected').text());
    $('#to').append($('#to-select :selected').text());
    $('fieldset').each(function() {
        var qty = $(this).find('input').val();
        if (qty > 0) {
            var product = $(this).find('select :selected').text();
            var html = '<tr><td>' + product + '</td><td>' + qty + '</td></tr>';
            modal.find('#details').append(html);
        }
    });
}).on('hide.bs.modal', function () {
    $(this).find('#details').empty();
    $(this).find('#from').empty();
    $(this).find('#to').empty();
});


// Gathers form data and compiles into a table for user confirmation for the create shipment form
$('#shipment-modal').on('show.bs.modal', function () {
    var modal = $(this);
    $('fieldset').each(function() {
        var qty = $(this).find('input').val();
        if (qty > 0) {
            var product = $(this).find('select :selected').text();
            var html = '<tr><td>' + product + '</td><td>' + qty + '</td></tr>';
            modal.find('#details').append(html);
        }
    });
}).on('hide.bs.modal', function () {
    $(this).find('#details').empty();
});

$('#report-filter').on('click', function() {

    var start = "start=" + $('#start').val();
    var end = "end=" + $('#end').val();
    var store = "store=" + $('#store-select :selected').val();
    var type = "type=" + $('#report-type :selected').val();
    var url = "/admin/store/report/sales?" + start + '&' + end + '&' + store + '&' + type;
    window.location.href = url;
});

$('.read-more').on('click', function () {
    var id = $(this).attr('data-id');
    $.getJSON('/announcements/' + id + '/show', function(data) {
        var anncmnt = data;
        $('#title').html(anncmnt.title);
        $('#user-name').html(anncmnt.user);
        $('#announcement-content').html(anncmnt.content);
        $('#created-at').html(anncmnt.created);
        $('#announcement-id').val(id);
        $('#comments').html('');
        $.each(anncmnt.comments, function(key, value) {
            $('#comments').append('<div class="well"><p>' + value.content + '</p><h6>By: ' + value.user + ' - ' + value.date + '</h6></div>')
        })
    });
    $('#announcement-modal').modal('show');
});
$('#post-reply').on('click', function() {
    tinyMCE.triggerSave();
    var replyContent = $('textarea#reply').val();
    $.ajax({
        url: '/announcements/' + $('#announcement-id').val() + '/add-comment',
        type: "POST",
        dataType: "json",
        data: {
            'content' : replyContent
        }
    }).done(function( json ) {
        if(json == 'success') {
            $('#comments').append('<div class="well">' + replyContent + '</div>');
            tinyMCE.activeEditor.setContent('');
        }
    });
});

$('div.alert').not('.alert-important').delay(3000).fadeOut(250);

