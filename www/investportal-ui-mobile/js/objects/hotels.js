const RangeLoad = () => {
    var range = $('.objects-adding-search > footer #right-content .range-form input[type="range"]');

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
    var rangetext = $('.objects-adding-search > footer #left-content .input-from-to:nth-child(1) .content-form input#to');
    var rangeeventext = $('.objects-adding-search > footer #left-content .input-from-to:nth-child(2) .content-form input#to');
    var range = $('.objects-adding-search > footer #right-content .range-form:nth-child(1) input[type="range"]');
    var evenrange = $('.objects-adding-search > footer #right-content .range-form:nth-child(2) input[type="range"]');

    range.bind('input', function (e) {
        var val = $(this).val();
        var curmax = rangetext;
        var query;
        query = val + " m2";
        curmax.val(query);
    });
    evenrange.bind('input', function (e) {
        var val = $(this).val();
        var curmax = rangeeventext;
        var query;
        query = val;
        curmax.val(query);
    });


}
$(document).ready(function () {
    RangeLoad();
    RangeInput();
});