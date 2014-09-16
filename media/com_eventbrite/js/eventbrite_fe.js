jQuery(document).ready(function($) {
    $.ajax({
        url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw&eid=1',
        type: "GET",
        dataType: "json",
        success: function(data) {
            table = $('#event-list');
            $.each(data, function(){
                table.append('<tr><td><a href="' + this.link +'" target=_"blank">' + this.name + '</a></td><td>' + this.tickets_remaining +'</td></tr>');
                // console.log(this);
            })

            $('#loader').fadeOut();
        }
    });
});