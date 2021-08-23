function ScreenRedirect(service){
    $('#auth-lightbox > .module-page').attr('style','display: none;');

    $('.module-page[data-screen="'+ service +'"]').removeAttr('style');
}

function OpenHomeScreen(e,t){
    e.preventDefault();

    ScreenRedirect('SignIn');
}


function OpenStep(service,step){
    $('.module-page[data-screen="'+ service +'"] main #reg-content li').attr('style','display: none;');

    $('.module-page[data-screen="'+ service +'"] main #reg-content li[data-signstep="'+ step +'"]').removeAttr('style');


}

function ScreenNavigator(e,t) {
    e.preventDefault();
    
    const curLink = $('.module-page > footer ul li').eq($(this).index());
    let getService = curLink.data('screenlocation');

    ScreenRedirect(getService);

}

const ScreenInit = () => {
    $('.header > #header_bottom .hb_bottom header .header-informer footer .user-services a, .header > #header_bottom_adaptive header ul.adaptive-buttons li:nth-last-child(2)').click(OpenHomeScreen);
    $('.module-page > footer ul li').click(ScreenNavigator);
}