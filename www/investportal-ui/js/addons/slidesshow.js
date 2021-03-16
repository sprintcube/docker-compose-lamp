const SliderSwitcher_Back = (cntl,slider) => {
    cntl.click(function (e) { 
        var sllib = slider;
        var eli;

        for (let index = 0; index < sllib.length; index++) {
            const switcher = sllib.eq(index);
            
            if(switcher.attr('id') == 'hide'){
                if(index == 0){ eli = sllib.length - 1; }
                else{ eli = index - 1; }
                break;
            }
        }

        sllib.attr('id','hide');

        var switching = sllib.eq(eli);

        switching.attr('id','');
    });
}
const SliderSwitcher_Go = (cntl,slider) => {
    cntl.click(function (e) { 
        var sllib = slider;
        var eli;

        for (let index = 0; index < sllib.length; index++) {
            const switcher = sllib.eq(index);
            
            if(switcher.attr('id') == 'hide'){
                if(index == (sllib.length - 1)){ eli = 0; }
                else{ eli = index + 1; }
                break;
            }
        }

        sllib.attr('id','hide');

        var switching = sllib.eq(eli);

        switching.attr('id','');
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
        $('#estate > main header#slider-controller img'),
        $('#reviews > main header#slider-controller img')
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