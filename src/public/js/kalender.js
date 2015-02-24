
$(document).ready(function() {
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: '', // 'title'
			right: 'agendaWeek,agendaDay'
		},
		lang: 'de',
		allDaySlot: false,
		handleWindowResize: true,
		slotDuration: '00:30:00',
		minTime: '07:00:00',
		maxTime: '20:00:00',

		weekMode: 'liquid', 
		defaultView: 'agendaWeek', 
		windowResize: function(view) {

			if ($(window).width() < 700){
				$('#calendar').fullCalendar( 'changeView', 'agendaDay' );
			} else {
				$('#calendar').fullCalendar( 'changeView', 'agendaWeek' );
			}
		},
      
		editable: false,
		events: '/kalenderjson',
		//events: '/events.json',
		eventDataTransform: function (rawEventData) {
			
            return {
                id: rawEventData.id,
                title: rawEventData.title,
                start: rawEventData.start,
                end: rawEventData.end,
                url: rawEventData.url//,
                //color: rawEventData.color
            };
        },
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
	});
		
});
