/**
 * Created by antoine on 16/04/17.
 */
$(function() {
    var workflow = document.getElementById("workflow") ;

    result = Viz(workflow.innerText, { format: "png-image-element", scale: 1 });
    result.style.width = '100%';
    result.style.height = 'auto';
    result.style.maxHeight = '400px';

    workflow.removeChild(workflow.firstChild);
    workflow.appendChild(result);
    workflow.style.display='block';
});