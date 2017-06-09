$(function () {
    var sendMail = $("input[name='complex_state[sendMail]']");
    var customMailMessageFormGroup = $('#customMailMessageFormGroup');

    $(sendMail).change(function () {
        if ($(this).val() == 1) {
            $(customMailMessageFormGroup).show();
        } else {
            $(customMailMessageFormGroup).hide();
        }
    });
});