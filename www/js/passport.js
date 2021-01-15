const lightboxopen = (e,t) => {
    $('#lightbox').removeClass('lightbox-closed');
    $('.lightwin-passport').removeClass('lightbox-closed');
}
const lightboxclose = (e,t) => {
    $('#lightbox').addClass('lightbox-closed');
    $('.lightwin-passport').addClass('lightbox-closed');
}
$(document).ready(function () {
    $('.add-but').click(lightboxopen);
    $('.lightwin-passport > header #right-content').click(lightboxclose);
});