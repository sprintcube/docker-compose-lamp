const InitSliders = (el) => {
    el.flickity({
        pageDots: false,
        wrapAround: true,
        freeScroll: true
    });
}
$(document).ready(function () {
    let sliders = [$('#promo > main .promo-feed'),$('#investsearch > main .popular-objects')];

    for (let i = 0; i < sliders.length; i++) {
        const slider = sliders[i];
        InitSliders(slider);
    }
});