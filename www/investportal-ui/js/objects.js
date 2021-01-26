const searchwinclose = (e,t) => {
    e.preventDefault();
    $('#objects > .projects-search-form').addClass('window-closed');
}
$(document).ready(function () {
    $('#objects > .projects-search-form header #right-content .close').click(searchwinclose);
    
});