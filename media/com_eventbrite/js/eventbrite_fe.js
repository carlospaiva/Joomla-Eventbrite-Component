jQuery(document).ready(function($) {
    $.ajax({
        url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw&eid=1',
        type: "GET",
        dataType: "json",
        success: function(data) {
            table = $('#event-list');
            $.each(data, function(){
               var markup = '<tr>';

                if (this.tickets_remaining == 'SOLD OUT') {
                    markup += '<td><h5>' + this.name + '</h5></td>';
                    markup += '<td><span class="btn btn-inverse btn-small">' + this.tickets_remaining + '</span></td>';
                }
                else {
                    markup += '<td><h5><a href="' + this.link + '" target=_"blank">' + this.name + '</a></h5></td>';
                    markup += '<td><a href="' + this.link + '" target="_blank" class="btn btn-success btn-small">' + this.tickets_remaining + ' Left - Buy Now</a></td>';
                }

                markup += '<td>' + this.price_range.lowest + ' - ' + this.price_range.highest + '</td>';
                markup += '</tr>';

                table.append(markup);
                // console.log(this);
            })

            $('.loader').fadeOut();
        }
    });
});