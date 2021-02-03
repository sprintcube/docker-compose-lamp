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
const HomePageHorizontalAdaptiveLine = () => {
    let vw = $(this).width();
    let margin;
    if(vw == 1024 || vw == 1280 || vw < 1280){
        margin = "0%";
    }
    else if(vw == 1280 || vw == 1366 || vw < 1366){
        margin = "17px";
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
    MarketingHeadBannerAdaptive();
    HomePageHorizontalAdaptiveLine();


    $(window).resize(MarketingHeadBannerAdaptive);
    $(window).resize(HomePageHorizontalAdaptiveLine);
});