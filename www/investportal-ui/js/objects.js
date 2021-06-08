var currentSlide = 0,
    n;


const searchwinclose = (e,t) => {
    e.preventDefault();
    $('#objects > .projects-search-form').addClass('window-closed');
}
$(document).ready(function () {
    $('#objects > .projects-search-form header #right-content .close').click(searchwinclose);
});

const ServiceSliderSwitcher_Back = (cntl,slider) => {
    cntl.click(function (e) { 
        var sllib = slider;
        n = currentSlide - 1;
        
        sllib.eq(currentSlide).attr('id','hide');
        currentSlide = (n+sllib.length)%sllib.length;
        sllib.eq(currentSlide).attr('id','');

    });
}
const ServiceSliderSwitcher_Go = (cntl,slider) => {
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
        $('#services > main header#slider-controller img'),
        $('#services > main header#slider-controller-adaptive img')
    ];
    let controlsGo = [
        $('#services > main footer#slider-controller img'),
        $('#services > main footer#slider-controller-adaptive img')
    ];
    let sliders = [
        $('#services > main #slider-view .service-feed .service'),
        $('#services > main #slider-view-adaptive .service')
    ];

    for (let i = 0; i < sliders.length; i++) {
        const e = sliders[i],
              g = controlsGo[i],
              b = controlsBack[i];

        ServiceSliderSwitcher_Back(b,e);
        ServiceSliderSwitcher_Go(g,e);
        
    }

    
});