let currentSlide = 0,
    n;

const SliderSwitcher_Back = (cntl,slider) => {
    cntl.click(function (e) { 
        var sllib = slider;
        n = currentSlide - 1;
        
        sllib.eq(currentSlide).attr('id','hide');
        currentSlide = (n+sllib.length)%sllib.length;
        sllib.eq(currentSlide).attr('id','');

    });
}
const SliderSwitcher_Go = (cntl,slider) => {
    cntl.click(function (e) { 
        var sllib = slider;
        n = currentSlide + 1;
        
        sllib.eq(currentSlide).attr('id','hide');
        currentSlide = (n+sllib.length)%sllib.length;
        sllib.eq(currentSlide).attr('id','');
    });
}

$(document).ready(function () {
    let controlsBack = [
        $('#news > main header#slider-controller img'),
        $('#services > main header#slider-controller img'),
        $('#analytics > main header#slider-controller img'),
        $('#estate > main header#slider-controller img'),
        $('#reviews > main header#slider-controller img')
    ];
    let controlsGo = [
        $('#news > main footer#slider-controller img'),
        $('#services > main footer#slider-controller img'),
        $('#analytics > main footer#slider-controller img'),
        $('#estate > main footer#slider-controller img'),
        $('#reviews > main footer#slider-controller img')
    ];
    let sliders = [
        $('#news > main #slider-view .news-feed'),
        $('#services > main #slider-view .service-feed'),
        $('#analytics > main #slider-view .analytic-feed'),
        $('#estate > main #slider-view .estat-feed'),
        $('#reviews > main #slider-view .review-feed')
    ];

    for (let i = 0; i < sliders.length; i++) {
        const e = sliders[i],
              g = controlsGo[i],
              b = controlsBack[i];


        SliderSwitcher_Back(b,e);
        SliderSwitcher_Go(g,e);
        
    }
});