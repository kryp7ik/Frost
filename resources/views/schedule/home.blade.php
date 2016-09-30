@extends('master')
@section('title', 'Schedule')
@section('content')

    <div id='wrap'>

        <div id='external-events'>
            <div id='external-events-listing'>
                <h4>Draggable Events</h4>
                <div class='fc-event' data-duration="03:00">My Event 1</div>
                <div class='fc-event'>My Event 2</div>
                <div class='fc-event'>My Event 3</div>
                <div class='fc-event'>My Event 4</div>
                <div class='fc-event'>My Event 5</div>
            </div>
        </div>

        <div id='calendar'></div>

        <div style='clear:both'></div>

    </div>

    <div id="event-actions">
        <div class="panel panel-info">
            <div class="panel-heading">
                Edit Event
            </div>
            <div class="panel-body">
                <p id="name"></p>
            </div>
        </div>
    </div>
@endsection
@push('css')
<link href="/calendar/fullcalendar.min.css" rel="stylesheet" />
<style>
    #external-events {
        float: left;
        width: 10%;
        padding: 0 10px;
        border: 1px solid #ccc;
        background: #eee;
        text-align: left;
    }

    #external-events h4 {
        font-size: 16px;
        margin-top: 0;
        padding-top: 1em;
    }

    #external-events .fc-event {
        margin: 10px 0;
        cursor: pointer;
    }

    #external-events p {
        margin: 1.5em 0;
        font-size: 11px;
        color: #666;
    }

    #external-events p input {
        margin: 0;
        vertical-align: middle;
    }

    #calendar {
        float: right;
        width: 88%;
    }

    #event-actions {
        display:none;
    }

</style>
@endpush
@push('scripts')
<script src="/calendar/fullcalendar.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script>
    function makeEventsDraggable() {
        $( ".fc-draggable" ).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: false
        });
    }

    $(document).ready(function() {
        var dragged = null;
        /* initialize the external events
         -----------------------------------------------------------------*/

        $('#external-events .fc-event').each(function() {

            $(this).data('event', {
                title: $.trim($(this).text()),
                stick: true
            });

            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0
            });

        });


        /* initialize the calendar
         -----------------------------------------------------------------*/

        var calendar = $('#calendar').fullCalendar({
            height: 700,
            allDaySlot: false,
            //defaultDate: '2016-10-01',
            header: {
                left: 'prev,next today',
                center: 'title',
            },
            firstDay: 5,
            defaultView: 'agendaWeek',
            minTime: '09:00:00',
            maxTime: '22:00:00',
            editable: true,
            droppable: true,
            dragRevertDuration: 0,
            drop: function(date) {
                // Add event to database where start is the date passed in this function
                // set end date to 2 hours later than the start
                // return the db ID and set to object
                alert("drop()" + date.format());
            },
            eventDragStop: function( event, jsEvent, ui, view ) {
                makeEventsDraggable();
            },
            eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
                // Resize only changes the end time so update db accordingly
                makeEventsDraggable();
                event.id = 2;
                event.categoryname = "Test Attribute";
                alert("eventResize() " + event.end);

            },
            viewRender: function() {
                makeEventsDraggable();
            },
            eventDragStart:function( event, jsEvent, ui, view ) {
                dragged = [ calendar, event ];
            },
            eventDrop: function(event, delta, revertFunc) {
                // Event drop could potentially change start and end times so update both in the db
                alert("eventDrop()" + event.id);
            },
            eventRender: function(event, element) {
                element.append('This is a test');
                element.bind('click', function (e) {
                        $("#name").html(event.title);
                        $("#event-actions").fadeIn();
                });
            }
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
</script>
@endpush