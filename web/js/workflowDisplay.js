/**
 * Created by antoine on 16/04/17.
 */
$(function() {
    var workflows = $('.workflow-dump');
    workflows.each(function(index) {
        var result = Viz(this.innerText, { format: "png-image-element", scale: 1 });

        result.style.width = '100%';
        result.style.height = 'auto';
        result.style.maxHeight = '400px';

        this.removeChild(this.firstChild);
        this.appendChild(result);
        this.style.display='block';
    });
});