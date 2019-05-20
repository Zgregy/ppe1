jQuery(document).ready(function() {

    var json = "";

    jQuery.ajax({
        type: 'POST',
        async: false,
        url : "./api/calendrier.php",
    }).done(function(response) {
        json = JSON.parse(response);
        console.log(json);

        jQuery('#calendar').fullCalendar({
            locale : "fr",
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultDate: '2019-01-12',
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: json,
        });
    });
});

