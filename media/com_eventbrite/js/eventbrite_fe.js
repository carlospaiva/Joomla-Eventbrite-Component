jQuery(document).ready(function($) {
    $.ajax({
        url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw&eid=' + $('#eid').val(),
        type: "GET",
        dataType: "json",
        success: function(data) {
            table = $('#event-list');
            $.each(data, function(){
               var markup = '<tr>';

                if (this.tickets_remaining == 'UNAVAILABLE') { 
                    markup += '<td><h5>' + this.name + '</h5></td>';
                    markup += '<td><span class="btn btn-inverse btn-small btn-block">' + this.tickets_remaining + '</span></td>';
                }
                else {
                    markup += '<td><h5><a href="' + this.link + '" target=_"blank">' + this.name + '</a></h5></td>';
                    markup += '<td><a href="' + this.link + '" target="_blank" class="btn btn-success btn-small btn-block">' + ticketLabel(this.tickets_remaining) + ' - Buy Now</a></td>';
                }

                markup += '<td><h5>' + this.price_range.lowest + ' - ' + this.price_range.highest + '</h5></td>';
                markup += '</tr>';

                table.append(markup);
                // console.log(this);

                // once we're done update the auto height library
                $.fn.matchHeight._update()
            });
            // hide the loader
            hideLoader();
        },
        error: function(data) {
            $('#event-list').after('<div class="alert alert-error">There was an error getting events. Please notify the Seneca Lake Wine Trail! <br />' + data.status + ' ' + data.statusText + '</div>');

            // hide the loader
            hideLoader();
        }
    });

    function hideLoader() {
        $('.loader').fadeOut();
    }

    function ticketLabel(ticketCount)
    {
        return '<span class="badge">' + ticketCount + ' Left</span>';
    }
});