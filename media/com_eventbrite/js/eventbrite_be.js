jQuery(document).ready(function($) {

    $("#jform_eventbrite_ids").chosen();

    // set the event list
    $.ajax({
        url: 'index.php?option=com_eventbrite&view=ajaxevents&format=raw',
        type: "GET",
        dataType: "json",
        success: function(data) {
            selectList = $('#jform_eventbrite_ids');
            $.each(data, function(){
                selectList.append( '<option value="' + this.id + '">' + this.name + '</option>');
            });

            // console.log($("#jform_eventbrite_ids_chzn").trigger("liszt:updated"));
            $("#jform_eventbrite_ids").trigger("liszt:updated");
        }
    });
});