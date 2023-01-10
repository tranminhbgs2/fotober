$(document).ready(function() {
    // Basic
    // $('.dropify').dropify();
    $("input[name$='link_upload']").click(function() {
        var value = $(this).val();

        $(".form-convert").hide();
        $("#" + value).show();
    });
});