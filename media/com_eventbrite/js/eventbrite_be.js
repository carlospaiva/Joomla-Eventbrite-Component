jQuery(document).ready(function($) {

    $("#jform_eventbrite_ids").chosen();

    // set the event list
    $.ajax({
        url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw',
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log('success');

            eventList = data;

            console.log(data.length);

            selectList = $('#jform_eventbrite_ids');

            markup = '';

            $.each(data, function(){

                markup = markup + '<option value="' + this.id + '">' + this.name + '</option>';

                selectList.append(markup);

                //console.log(this);
            });

            // console.log($("#jform_eventbrite_ids_chzn").trigger("liszt:updated"));
            $("#jform_eventbrite_ids").trigger("liszt:updated");
        }
    });
});