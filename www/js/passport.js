const lightboxopen = (e,t) => {
    $('#lightbox').removeClass('lightbox-closed');
    $('.lightwin-passport').removeClass('lightbox-closed');
    $('.lightwin-passport-regional').removeClass('lightbox-closed');
}
const lightboxclose = (e,t) => {
    e.preventDefault();

    $('#lightbox').addClass('lightbox-closed');
    $('.lightwin-passport').addClass('lightbox-closed');
    $('.lightwin-passport-regional').addClass('lightbox-closed');
}
const lightboxcloseRegional = (e,t) => {
    e.preventDefault();

    $('.lightwin-passport-regional').addClass('lightbox-closed');
}
$(document).ready(function () {
    $('.add-but').click(lightboxopen);
    $('.lightwin-passport > header #right-content').click(lightboxclose);
    $('.lightwin-passport-regional > header #right-content').click(lightboxcloseRegional);
});