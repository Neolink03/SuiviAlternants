$(function () {
    var sendMail = $("input[name='complex_state[sendMail]']");
    var customMailMessageFormGroup = $('#customMailMessageFormGroup');

    if ($(sendMail).val() == 0) {
        $(customMailMessageFormGroup).hide();
    }

    $(sendMail).change(function () {
        if ($(this).val() == 1) {
            $(customMailMessageFormGroup).show();
        } else {
            $(customMailMessageFormGroup).hide();
        }
    });
});