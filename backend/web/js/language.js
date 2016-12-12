
/**
 * Created by vaishakhi on 24/10/16.
 * language related functionality
 */
$(function () {
    $(document).on('click', '.language', function () {
        var lang = $(this).attr("id");
        url = siteUrl + 'backend/web/index.php/site/language';
        $.post(url, {"lang": lang}, function (data) {
            location.reload();
        });
    });
});