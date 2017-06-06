$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$(function () {
    $('#select-all-by-status').change(function () {
        var stateSelected = $(this).val();
        var checkboxes = $(':checkbox');
        
        $(checkboxes).each(function () {
            this.checked = false;
        });

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