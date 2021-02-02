const RangeLoad = () => {
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
    var rangetext = $('.input-from-to:nth-child(odd) > .content-form input#to');
    var rangeeventext = $('.input-from-to:nth-child(even) > .content-form input#to');
    var range = $('.range-form:nth-child(odd) > input[type="range"]');
    var evenrange = $('.range-form:nth-child(even) > input[type="range"]');

    range.bind('input', function (e) {
        var val = $(this).val();
        var curmax = rangetext.eq(range.index(this));
        var query;
        query = val + " m2";
        curmax.val(query);
    });
    evenrange.bind('input', function (e) {
        var val = $(this).val();
        var curmax = rangeeventext.eq(evenrange.index(this));
        var query;
        query = val;
        curmax.val(query);
    });


}
$(document).ready(function () {
    RangeLoad();
    RangeInput();
});