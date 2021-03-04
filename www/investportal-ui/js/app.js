//Works
var host;
if (location.hostname === "investportal-ui.aplex"){ host = "http://investportal-ui.aplex"; }
else{ host = "http://zolotaryow.aplex.ru/invest";}

let notiaudio = [
    new Audio(host + '/audios/chat_open.mp3'),
    new Audio(host + '/audios/chat_close.mp3')
];
const OpenChat = () => {
    window.setTimeout(function(){
      $('#chat-lightbox').removeClass('lightbox-closed');
      notiaudio[0].play();
    },10000);
}
const CloseChat = () => {
    var close = $('#chat-lightbox header img');
    
    close.click(function(){
      $('#chat-lightbox').addClass('lightbox-closed');
      notiaudio[1].play();
    });
}
  
const OnlineChat = () => {
    OpenChat();
    CloseChat();
}

const MarketingHeadBannerAdaptive = () => {
    let vw = $(this).width();
    let margin;
    if(vw == 1024 || vw == 1280 || vw < 1280){
        margin = "7%";
    }
    else if(vw == 1280 || vw == 1366 || vw < 1366){
        margin = "6%";
    }
    else if(vw == 1366 || vw == 1400 || vw < 1400){
        margin = "6%";
    }
    else if(vw == 1400 || vw == 1600 || vw < 1600){
        margin = "5%";
    }
    else if(vw == 1600 || vw == 1800 || vw < 1800){
        margin = "5%";
    }
    else{ margin = "5%"; }

    $('.banner-block').css("margin-left", margin);
    
}
const PromoBlocksThreeDetals = () => {
    let promos = [$('#promo > main .promo-feed .promoblock'),$('main .popular-objects .popular-object')];
    let threed = $('#threedetal-left, #threedetal-right, #threedetal-left-chromium, #threedetal-right-chromium');


    for (let index = 0; index < promos.length; index++) {
        const el_size = promos[index];
        if(window.chrome){ threed.height(el_size.height() - 110); }
        else{ threed.height(el_size.height() - 100); }
        
    }
}
const PromoBlocksThreeDetalsAppend = () => {
    let promos = [$('#promo > main .promo-feed .promoblock'),$('main .popular-objects .popular-object')];


    for (let index = 0; index < promos.length; index++) {
        const el_size = promos[index];
        if(!index == 1){
            if(window.chrome){
                el_size.before('<div id="threedetal-left-chromium"><span style="width: 103vw;height: 100%;background-color: white;margin-left: 1%;margin-right: 2vw;">&nbsp;</span></div>');
                el_size.after('<div id="threedetal-right-chromium"><span style="width: 90vw;height: 100%;background-color: white;margin-right: 3vw;">&nbsp;</span></div>');
            }
            else{
                el_size.before('<div id="threedetal-left"><span style="width: 113vw;height: 100%;background-color: white;margin-left: -1%;">&nbsp;</span></div>');
                el_size.after('<div id="threedetal-right"><span style="width: 100vw;height: 100%;background-color: white;">&nbsp;</span></div>'); 
            }
        }
        else{
            if(window.chrome){
                el_size.before('<div id="threedetal-left-chromium"><span style="width: 106vw;height: 100%;background-color: white;margin-left: -5%;padding-right: 4px;">&nbsp;</span></div>');
                el_size.after('<div id="threedetal-right-chromium"><span style="width: 93vw;height: 100%;background-color: white;margin-left: -4%;padding-right: 2px;">&nbsp;</span></div>');
            }
            else{
                el_size.before('<div id="threedetal-left"><span style="width: 116vw;height: 100%;background-color: white;margin-left: -5%;padding-right: 4px;">&nbsp;</span></div>');
                el_size.after('<div id="threedetal-right"><span style="width: 103vw;height: 100%;background-color: white;margin-left: -4%;padding-right: 2px;">&nbsp;</span></div>');
            }
        }
    }
}
const HomePageHorizontalAdaptiveLine = () => {
    let vw = $(this).width();
    let margin;
    if(vw == 1280){
        margin = "5px";
    }
    else if(vw == 1024 || vw == 1280 || vw < 1280){
        margin = "27px";
    }
    else if(vw == 1280 || vw == 1366 || vw < 1366){
        margin = "4%";
    }
    else if(vw == 1366 || vw == 1400 || vw < 1400){
        margin = "-8%";
    }
    else if(vw == 1400 || vw == 1600 || vw < 1600){
        margin = "-12%";
    }
    else if(vw == 1600 || vw == 1800 || vw < 1800){
        margin = "2%";
    }
    else{ margin = "2%"; }

    $('#promo > header hr').css({"position": "relative", "top": margin});
}
const VideoPlayerUI = (player) => {
    var video = $(player).get(0);
    var watch = $('.video-player > .play img');
    var pause = $('.video-player .controls .pause');
    var sc = [$('.video-player .controls .sound'), $('.video-player .controls .nosound')];
    watch.click(function () { 
        var scc;
        $(this).addClass('watching');
        video.play();
        pause.removeClass('watching');
        sc[0].removeClass('watching');

        video.volume = 1;

        sc[0].removeClass('watching');
    });

    
    pause.click(function () { 
        watch.removeClass('watching');
        video.pause(); 
        pause.addClass('watching');
        sc[0].addClass('watching');
        sc[1].addClass('watching');
    });

    sc[1].click(function () { 
        $(this).addClass('watching');
        sc[0].removeClass('watching');

        video.volume = 1;
    });
    sc[0].click(function () { 
        $(this).addClass('watching');
        sc[1].removeClass('watching');

        video.volume = 0;
        
    });
}
$(document).ready(function () {
    //OnlineChat();
    PromoBlocksThreeDetalsAppend();
    PromoBlocksThreeDetals();
    MarketingHeadBannerAdaptive();
    HomePageHorizontalAdaptiveLine();

    $(window).resize(MarketingHeadBannerAdaptive);
    $(window).resize(PromoBlocksThreeDetals);
    $(window).resize(HomePageHorizontalAdaptiveLine);


});


//Paths
const ESAdaptivePath = () => {
    var es = $('.exchange-selector');
    var hi = $('.header > #header_bottom .hb_bottom header .header-informer');
    var vw = $(window).width();

    if (vw == 1280 || vw == 1400 || vw < 1400) { 
        es.addClass('exchange-adaptive-path'); 
        hi.addClass('header-informer-path'); 
    }
    else { 
        es.removeClass('exchange-adaptive-path'); 
        hi.removeClass('header-informer-path'); 
    }
}

const LastMenuLists = () => {
    let els = [$('#menu-image'), $('.header > #header_bottom .hb_bottom footer nav')];

    var menufont = els[1].css("font-size");

    els[0].css("font-size", menufont);
}

$(document).ready(function () {
    LastMenuLists();
    ESAdaptivePath();
    $(window).resize(ESAdaptivePath);
    $(window).resize(LastMenuLists);
});