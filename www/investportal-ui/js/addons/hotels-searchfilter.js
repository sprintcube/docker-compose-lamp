const LBFilter = () => {
    $('#mobile-form-popup > header a').click(function(e,t){
      e.preventDefault();
      LBShower(1);
    });
    
    $('#mobile-form-popup > footer button.add-but-popup').click(function(e,t){
      let curBut = $(this).eq($(this).index()).attr('id');
      
      switch(curBut){
        case 'close':
          LBShower(0);
        break;
        default: e.preventDefault(); break;
      }
    });
    
}
  
$(document).ready(LBFilter);
  
const LBShower = (st) => {
    const lightbox = [
      $('#mobile-form-popup > main'),
      $('#mobile-form-popup > footer')
    ];
    
    for(let i = 0; i < lightbox.length; i++){
      const curLBC = lightbox[i];
      
      if(st == 1) curLBC.removeClass('lb-filter-close');
      else curLBC.addClass('lb-filter-close');
    }
}