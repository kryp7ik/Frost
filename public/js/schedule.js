function makeEventsDraggable() {
    $( ".fc-draggable" ).draggable({
        zIndex: 999,
        revert: true,
        revertDuration: false
    });
}

/* Calculates the clocked in hours for each employee for the given day or week and displays it
------------------------------------------------------------------------------------------------ */
function getClockedHours(eventArray) {
    var totalClocked = {};
    $.each(eventArray, function(key,value) {
        if (value.in != 0 && value.out !=0) {
            var clocked = (moment(value.out).format('X') - moment(value.in).format('X')) / 60 / 60;
            if (value.user.name in totalClocked) {
                totalClocked[value.user.name] += clocked;
            } else {
                totalClocked[value.user.name] = clocked;
            }
        }
    });
    $('#clocked').html('');
    $.each(totalClocked, function(key, value) {
        $('#clocked').append('<li>' + key + ' : ' + $.number(value,2) + '</li>');
    });
}

/* Setup CSRF token for ajax requests
----------------------------------------- */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    }
});

/* Checks if the logged in user is a manager in order to grant edit permissions
------------------------------------------------------------------------------- */
var userCanEdit = function() {
    var manager = null;
    $.ajax({
        async: false,
        url: '/user/status',
        type: 'GET',
        success: function (data) {
            manager = data.manager
        }
    });
    return manager;
}();

$(document).ready(function() {
    var dragged = null;

    /* initialize the external events
     -----------------------------------------------------------------*/
    $('#external-events .fc-event').each(function() {

        $(this).data('event', {
            title: $.trim($(this).text()),
            stick: true,
            storeid: $(this).attr('storeid'),
            color: $(this).attr('color'),
            user: $(this).attr('user'),
            in: 0,
            out: 0,
        });

        $(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
        });

    });

    /* On select change update draggable events "storeid" attribute
     -----------------------------------------------------------------*/
    $('#store-select').on('select2:select', function() {
        $('.fc-event').each(function() {
            $(this).attr('storeid', $('#store-select').val());
            $(this).data('event', {
                title: $.trim($(this).text()),
                stick: true,
                storeid: $('#store-select').val(),
                color: $(this).attr('color'),
                user: $(this).attr('user'),
                in: 0,
                out: 0,
            });
        });
    });

    /* initialize the calendar
     -----------------------------------------------------------------*/
    var calendar = $('#calendar').fullCalendar({
        height: 650,
        allDaySlot: false,
        header: {
            left: 'agendaDay,agendaWeek',
            center: 'title',
            right: 'prev,next today'
        },
        firstDay: 5,
        defaultView: 'agendaDay',
        minTime: '09:00:00',
        maxTime: '22:00:00',
        editable: userCanEdit,
        droppable: userCanEdit,
        dragRevertDuration: 0,
        events: {
            url: '/shift',
            type: 'GET',
            error: function() {

            },
            success: function (data) {
                getClockedHours(data);
            },
        },
        eventReceive: function(event) {
            $.ajax({
                url: "/shift",
                type: "POST",
                dataType: "json",
                data: {
                    start: event.start.format(),
                    storeid: event.storeid,
                    user: event.user,
                    color: event.color
                }
            }).done(function( json ) {
                event.id = json.id;
            });

        },
        eventResize: function(event) {
            $.ajax({
                url: "/shift/" + event.id,
                type: "PUT",
                dataType: "json",
                data: {
                    start: event.start.format(),
                    end: event.end.format(),
                }
            });
            makeEventsDraggable();
        },
        eventDrop: function(event) {
            $.ajax({
                url: "/shift/" + event.id,
                type: "PUT",
                dataType: "json",
                data: {
                    start: event.start.format(),
                    end: event.end.format(),
                }
            });
        },
        eventDragStop: function() {
            makeEventsDraggable();
        },
        viewRender: function() {
            makeEventsDraggable();
        },
        eventDragStart:function(event) {
            dragged = [ calendar, event ];
        },
        eventRender: function(event, element) {
            element.append('Store: ' + $("#store-select").find("option[value='" + event.storeid + "']").text());
            if(event.in != 0) {
                element.append('<br/>In: ' + moment(event.in).format('hh:mma'));
            }
            if(event.out != 0) {
                element.append('<br/>Out: ' + moment(event.out).format('hh:mma'));
            }
            if (userCanEdit) {
                element.bind('click', function () {
                    $("#name").html(event.title);
                    $('#estore').html($("#store-select").find("option[value='" + event.storeid + "']").text());
                    $('#shift').html(moment(event.start).format('dddd MMMM D hh:mma') + ' - ' + moment(event.end).format('hh:mma'));
                    if (event.in != 0) {
                        $('#in').attr('value', moment(event.in).format('hh:mma'));
                    }
                    if (event.out != 0) {
                        $('#out').attr('value', moment(event.out).format('hh:mma'));
                    }
                    $('#shiftid').attr('value', event.id);
                    $('#del').attr('href', '/shift/' + event.id);
                    $("#event-actions").fadeIn();
                });
            }

        }
    });

    /* Bind the save, delete & Clock In/out buttons to ajax request
     ------------------------------------------------------- */
    $('#del').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'DELETE',
            dataType: "json",
        }).done(function() {
            calendar.fullCalendar('refetchEvents');
            $('#event-actions').fadeOut();
        })
    });
    $('#save').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: "/shift/" + $('#shiftid').val(),
            type: "PUT",
            dataType: "json",
            data: {
                in : $('#in').val(),
                out : $('#out').val(),
            }
        }).done(function( json ) {
            calendar.fullCalendar('refetchEvents');
            console.log(json);
        });
    });

    $('#clock').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: "/shift/clock",
            type: 'GET',
            dataType: 'json',
        }).done(function(json) {
            calendar.fullCalendar('refetchEvents');
            $('#clock-status').html(json);
        });
    });

    /* Make external-events droppable
     -----------------------------------------------------------------*/
    $('#external-events-listing').droppable({
        drop: function( event, ui ) {
            if ( dragged ) {
                var event = dragged[1];
                dragged[0].fullCalendar('removeEvents',event._id);
                var el = $( "<div class='fc-event'>" ).appendTo( this ).text( event.title );
                el.draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });
                el.data('event', { title: event.title, id :event.id, stick: true });
                dragged = null;
                makeEventsDraggable();
            }
        }
    });


});