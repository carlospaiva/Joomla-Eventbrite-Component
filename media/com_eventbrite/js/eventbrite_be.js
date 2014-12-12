jQuery(document).ready(function($) {

    $("#jform_eventbrite_ids").chosen();

    $("#submit-search").click(function() {
        $('#event-list tbody').empty();
        $('.loader').fadeIn();
        searchEvents();
    });

    // run on load
    searchEvents();

    function searchEvents()
    {
        // set the event list
        $.ajax({
            url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw&search=' + $('.search-events').val() + '&eid=' + $('#eid').val(),
            type: "GET",
            dataType: "json",
            success: function(data) {
                selectList = $('#jform_eventbrite_ids');
                var table = $('#event-list tbody');
                $.each(data, function(){
                    selectList.append( '<option value="' + this.id + '">' + this.name + '</option>');

                    var selected = '';

                    if (this.selected === true)
                    {
                        selected = 'checked';
                    }


                    var markup;

                    markup += '<tr>';
                    markup += '<td>' + this.name + '</td>';
                    markup += '<td>' + this.venue + '</td>';
                    markup += '<td>' + this.id +'</td>';
                    markup += '<td>' + '<input type="checkbox" value="' + this.id +'" name="jform[eventbrite_ids][]" ' + selected + ' class="event-select" />' + '</td>';
                    markup += '<td>' + '<a href="' + this.url + '" target="_blank"><i class="icon-home-2"></i>' + '</a></td>'
                    markup += '</tr>';

                    table.append(markup);
                });

                // console.log($("#jform_eventbrite_ids_chzn").trigger("liszt:updated"));
                $("#jform_eventbrite_ids").trigger("liszt:updated");
                $('.loader').fadeOut();
            },
            error: function(data) {
                $('#event-list').append('<div class="alert alert-error">There was an error getting the events</div>');
            }
        });
    }

    $('#select').click(function() {
        console.log('clicked');
        $('.event-select').each(function(){
            this.checked = true;
        });
    });

    $('#deselect').click(function() {
        console.log('clicked');
        $('.event-select').each(function(){
            this.checked = false;
        });
    });

});
