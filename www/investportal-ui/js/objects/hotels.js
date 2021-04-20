const RangeInput = () => {
    var rangetext = [
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(1) .content-form input#from'),
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(1) .content-form input#to')
    ];
    var rangeeventext = [
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(2) .content-form input#from'),
        $('.objects-adding-search > footer #left-content .input-from-to:nth-child(2) .content-form input#to')
    ];
    var range = $('.objects-adding-search > footer #right-content .range-form:nth-child(1) #slider-range');
    var evenrange = $('.objects-adding-search > footer #right-content .range-form:nth-child(2) #slider-range');

    range.slider({
      range: true,
      min: 0,
      max: 1130,
      values: [ 1, 565 ],
      slide: function( event, ui ) {
        for (let i = 0; i < rangetext.length; i++) {
            rangetext[i].val(ui.values[i] + ' m2');
        }
      }
    });
    evenrange.slider({
      range: true,
      min: 50,
      max: 20000,
      values: [ 100, 10000 ],
      slide: function( event, ui ) {
        for (let i = 0; i < rangeeventext.length; i++) {
            rangeeventext[i].val(ui.values[i]);
        }
      }
    });


    for (let i = 0; i < rangetext.length; i++) {
        rangetext[i].bind('input change',function(e,t){
            const v = $(this).val(),
                  r = range;
            let setFigure;
            if(v.indexOf(' m2') != -1){ setFigure = parseInt(v.slice(0, -3)); }
            else{ setFigure = parseInt(v); }

            r.slider('values',i,setFigure);
            $(this).val(setFigure + ' m2');
        });
    }
    for (let i = 0; i < rangeeventext.length; i++) {
        rangeeventext[i].bind('input change',function(e,t){
            const v = $(this).val(),
                  r = evenrange;

            r.slider('values',i,parseInt(v));
        }); 
    }

}
$(document).ready(function () {
    RangeInput();
});