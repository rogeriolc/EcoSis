<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- Bootstrap Core Css -->
	<link href="../../lib/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="../../lib/plugins/full-calendar/3.9.0/fullcalendar.min.css" rel="stylesheet">
</head>
<body>
	<div id="calendar">
	</div>

	<!-- Jquery Core Js -->
	<script src="../../lib/plugins/jquery/jquery.min.js"></script>

	<!-- Jquery Ui -->
	<script src="../../lib/plugins/jquery/jquery-ui.min.js"></script>

	<!-- Bootstrap Core Js -->
	<script src="../../lib/plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Moment Plugin Js -->
    <script src="../../lib/plugins/momentjs/moment.js"></script>

	<!-- Fullcalendar -->
	<script src="../../lib/plugins/full-calendar/3.9.0/fullcalendar.min.js"></script>
	<script src="../../lib/plugins/full-calendar/3.9.0/locale/pt-br.js"></script>

	<script type="text/javascript">
		$(function(){
			$('#calendar').fullCalendar({
				themeSystem: 'bootstrap3',
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay,listMonth'
				},
				weekNumbers: true,
                eventLimit: true, // allow "more" link when too many events
                events: 'https://fullcalendar.io/demo-events.json'
            });
		});
	</script>
</body>
</html>