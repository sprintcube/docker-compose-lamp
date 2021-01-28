const SliderControlUI = () => {
    $('#images-slider > .header header.switcher').click(function (e) { 
        var imglib = $('#images-slider > .footer .image-switcher img');
        var current = $('#images-slider > .content #current');
        var eli;

        for (let index = 0; index < imglib.length; index++) {
            const switcher = imglib.eq(index);
            
            if(switcher.hasClass('image-current')){
                if(index == 0){ eli = imglib.length - 1; }
                else{ eli = index - 1; }
                break;
            }
        }

        imglib.removeClass('image-current');

        var switching = imglib.eq(eli);

        switching.addClass('image-current');
        current.attr('src', switching.attr('src'));
    });

    $('#images-slider > .header footer.switcher').click(function (e) { 
        var imglib = $('#images-slider > .footer .image-switcher img');
        var current = $('#images-slider > .content #current');
        var eli;

        for (let index = 0; index < imglib.length; index++) {
            const switcher = imglib.eq(index);
            
            if(switcher.hasClass('image-current')){
                if(index == (imglib.length - 1)){ eli = 0; }
                else{ eli = index + 1; }
                break;
            }
        }

        imglib.removeClass('image-current');

        var switching = imglib.eq(eli);

        switching.addClass('image-current');
        current.attr('src', switching.attr('src'));
    });
    $('#images-slider > .footer .image-switcher').click(function (e,t) { 
        var imglib = $('#images-slider > .footer .image-switcher img');
        var current = $('#images-slider > .content #current');

        imglib.removeClass('image-current');

        var switching = imglib.eq($(this).index());

        switching.addClass('image-current');
        current.attr('src', switching.attr('src'));
    });
}
const SliderCurrentImageLoad = () => {
    var current = $('#images-slider > .content #current');
    var switching = $('.image-current');

    current.attr('src',switching.attr('src'));
}

$(document).ready(function () {
    SliderControlUI();
    SliderCurrentImageLoad();
});