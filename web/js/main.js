$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$(function () {
    $('#select-all-by-status').change(function () {
        var checkboxes = $(':checkbox');
        $(checkboxes).each(function () {
            this.checked = false;
        });

        var stateSelected = $(this).val();
        if (stateSelected === 'all') {
            $(checkboxes).each(function () {
                this.checked = true;
            });
        }

        $(checkboxes).each(function () {
            if ($(this).attr('data-state') === stateSelected) {
                this.checked = true;
            }
        });
    });
});