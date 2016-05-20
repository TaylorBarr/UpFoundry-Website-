jQuery(document).ready(function() {

    var url = window.location.href;
    var get_url = jQuery('#share-button-head').data('url');

    jQuery.getJSON(get_url, { url: url }, function(data) {

        jQuery("#totalshare").html(data.count + ' Shares');
        jQuery("#totalsharehead").html(data.count + ' Shares');

    });
    return false;

});