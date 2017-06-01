$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$(function () {
    $('#select-all').click(function (event) {
        var checked = this.checked;
        $(':checkbox').each(function () {
            this.checked = checked;
        });
    });
});