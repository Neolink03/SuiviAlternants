/**
 * Created by antoine on 16/04/17.
 */
$(function () {
    var workflows = $('.workflow-dump');

    workflows.each(function (index) {
        var result = Viz(this.innerText, {format: "png-image-element", scale: 1});

        result.style.maxWidth = '100%';
        result.style.height = 'auto';

        this.removeChild(this.firstChild);
        this.appendChild(result);
        this.style.display = 'block';
    });

    $(workflows).attr("data-scale", "2.4");

    var zoomed = false;
    $(workflows).on('click', function () {
        if (!zoomed) $(this).children('img').css({'transform': 'scale(' + $(this).attr('data-scale') + ')'});
        else $(this).children('img').css({'transform': 'scale(1)'});

        zoomed = !zoomed;

    }).on('mousemove', function (e) {
        if (zoomed)
            $(this).children('img').css({'transform-origin': ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 + '%'});
    })
});