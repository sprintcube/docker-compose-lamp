let sliders = ['#promo > main.swiper-container','#investsearch > main.swiper-container'];

for (let index = 0; index < sliders.length; index++) {
    const slider = sliders[index];

    var swiper = new Swiper(slider, {
                spaceBetween: -15,
                slidesPerView: 'auto',
                loop: true,
                loopFillGroupWithBlank: true,
                pagination: {
                    el: '.swiper-pagination'
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                scrollbar: {
                    el: '.swiper-scrollbar',
                    draggable: true
                }
      });      
 }
