const lightboxopen = (e,t) => {
    $('#lightbox').removeClass('lightbox-closed');
}

$(document).ready(function () {
    $('.add-but').click(lightboxopen);
});