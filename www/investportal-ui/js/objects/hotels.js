const RangeLoad = () => {
    var range = $('.objects-adding-search > footer #right-content .range-form input[type="range"]#max'),
        evenrange = $('.objects-adding-search > footer #right-content .range-form input[type="range"]#min');


    for (let i = 0; i < range.length; i++) {
        var min,max,step,res;
        var f,t;
        var level;
        var maxv;
        
        if(i == 0 || i % 2 == 0){
            maxv = 100;
            f = maxv / 2;
            t = 10000;
            level = t / f;
        }
        else{
            maxv = 1;
            f = 0;
            t = 565;
            level = 15;
        }
        
        
        min = f;
        max = t;
        step = level;
        res = maxv;
    
        range.eq(i).attr({
            'min': min,
            'max': max + 99,
            'step': step,
            'value': res
        });
    }
    for (let i = 0; i < evenrange.length; i++) {
        var min,max,step,res;
        var f,t;
        var level;
        var maxv;
        
        if(i == 0 || i % 2 == 0){
            maxv = 100;
            f = maxv / 2;
            t = 10000;
            level = t / f;
        }
        else{
            maxv = 1;
            f = 0;
            t = 565;
            level = 15;
        }
        
        min = f;
        max = t;
        step = level;
        res = maxv;
    
        evenrange.eq(i).attr({
            'min': min,
            'max': max + 99,
            'step': step,
            'value': res
        });
    }

    
}
const RangeInput = () => {
    var rangetext = [
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(1) .content-form input#from'),
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(1) .content-form input#to')
    ];
    var rangeeventext = [
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(2) .content-form input#from'),
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(2) .content-form input#to')
    ];
    var range = [
        $('.objects-adding-search > footer #right-content .range-form:nth-child(1) input[type="range"]#min'),
        $('.objects-adding-search > footer #right-content .range-form:nth-child(1) input[type="range"]#max')
    ];
    var evenrange = [
        $('.objects-adding-search > footer #right-content .range-form:nth-child(2) input[type="range"]#min'),
        $('.objects-adding-search > footer #right-content .range-form:nth-child(2) input[type="range"]#max')
    ];

    for (let i = 0; i < range.length; i++) {
        const a = range[i],
              b = evenrange[i],
              c = rangetext[i],
              d = rangeeventext[i];

        a.bind('input', function (e) {
                var val = $(this).val();
                var curmax = с;
                var query;
                query = val + " m2";
                curmax.val(query);
        });
        b.bind('input', function (e) {
                var val = $(this).val();
                var curmax = d;
                var query;
                query = val;
                curmax.val(query);
        });
        
    }


}
$(document).ready(function () {
    RangeLoad();
    RangeInput();
});