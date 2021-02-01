const RangeLoad = () => {
    var rangetext = [
        $('.input-from-to > .content-form input#from'), 
        $('input-from-to > .content-form input#to')
    ];
    var range = $('.range-form > input[type="range"]');

    for (let i = 0; i < range.length; i++) {
        var min,max,step,res;
        var f,t;
        var level;
        
        if(i == 0 || i % 2 == 0){
            f = 100;
            t = 10000;
            level = t / f;
        }
        else{
            f = 1;
            t = 565;
            level = 15;
        }
        
        var maxv = t;
        
        min = f;
        max = t;
        step = level;
        res = maxv;
    
        range.eq(i).attr({
            'min': min,
            'max': max,
            'step': step,
            'value': res
        });
    }
}
const RangeInput = () => {
    var rangetext = [
        $('.input-from-to > .content-form input#from'), 
        $('input-from-to > .content-form input#to')
    ];
    var range = $('.range-form > input[type="range"]');

    range.bind('input', function (e) {
        
    });
    range.bind('change', function (e) {
        
    });



}
$(document).ready(function () {
    RangeLoad();
    RangeInput();
});