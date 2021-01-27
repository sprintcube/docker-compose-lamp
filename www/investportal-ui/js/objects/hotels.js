const RangeLoad = () => {
    var rangetext = [
        $('#left-content > .input-from-to .content-form input#from,  header > .input-from-to .content-form input#from'), 
        $('#left-content > .input-from-to .content-form input#to,  header > .input-from-to .content-form input#to')
    ];
    var range = $('#right-content > .range-form input[type="range"], footer > .range-form input[type="range"]');

    for (let i = 0; i < range.length; i++) {
        var min,max,step,res;
        var f,t;
        
        if(rangetext[0].eq(i).val().indexOf('m2') && rangetext[1].eq(i).val().indexOf('m2')){
            f = rangetext[0].eq(i).val().substring(0,rangetext[0].eq(i).val().length - 3);
            t = rangetext[1].eq(i).val().substring(0,rangetext[1].eq(i).val().length - 3); 
        }
        else{
            f = rangetext[0].eq(i).val();
            t = rangetext[1].eq(i).val();
        }
    
        var maxv = t;
        var level = t / f;
        

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
        $('#left-content > .input-from-to .content-form input#from,  header > .input-from-to .content-form input#from'), 
        $('#left-content > .input-from-to .content-form input#to,  header > .input-from-to .content-form input#to')
    ];
    var range = $('#right-content > .range-form input[type="range"], footer > .range-form input[type="range"]');


}
const RangeChange = () => {
    var rangetext = [
        $('#left-content > .input-from-to .content-form input#from,  header > .input-from-to .content-form input#from'), 
        $('#left-content > .input-from-to .content-form input#to,  header > .input-from-to .content-form input#to')
    ];
    var range = $('#right-content > .range-form input[type="range"], footer > .range-form input[type="range"]');


}
$(document).ready(function () {
    RangeLoad();
    RangeInput();
    RangeChange();
});