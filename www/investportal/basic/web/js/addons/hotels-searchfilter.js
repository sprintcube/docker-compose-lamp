const LBFilter = () => {
    $('#mobile-form-popup > header a').click(function(e,t){
      e.preventDefault();
      LBShower(1);
    });
    
    $('#mobile-form-popup > footer button.add-but-popup').click(function(e,t){
      let curBut = $(this).eq($(this).index()).attr('id');
      
      switch(curBut){
        case 'save':
          LBShower(0);
        break;
        default: e.preventDefault(); break;
      }
    });
    
}
  
const ProblemContsResize = () => {
  let vw = $(window).width();

  if(!vw == 320 && !vw == 1024 && !vw < 1024){
    problemBox.removeClass('lightbox-opener');

    for(let i = 0; i < lightbox.length; i++){
      const cur = lightbox[i];

      cur.addClass('lb-filter-close');
    }
  }
}
$(document).ready(LBFilter);
$(document).ready(function(){
  $(window).resize(ProblemContsResize);
});
  
const LBShower = (st) => {
    const lightbox = [
      $('#mobile-form-popup > main'),
      $('#mobile-form-popup > footer')
    ],
          problemBox = $('body, #objects > .projects-search-form main, #objects > .projects-search-form footer');
    
    for(let i = 0; i < lightbox.length; i++){
      const curLBC = lightbox[i];
      
      if(st == 1) curLBC.removeClass('lb-filter-close');
      else curLBC.addClass('lb-filter-close');
    }

    if(st == 1) problemBox.addClass('lightbox-opener');
    else problemBox.removeClass('lightbox-opener');


}