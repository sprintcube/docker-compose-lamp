$(document).ready(function () {
    const but = $('.settings-form > .form-wrapper button'),
          butCont = $('.settings-form > .form-wrapper button i.far'),
          put = $('.settings-form > .form-wrapper .input');

    for (let index = 0; index < $('.settings-form > .form-wrapper').length; index++) {
        but.eq(index).click(function (e,t) { 

            const curPut = put.eq(index),
                  curBC = butCont.eq(index);
            let inputState = false,
                butState = "",
                butStateDel = "";

            if (!curPut.prop('disabled')) {
                inputState = true;
                butState = "fa-edit";
                butStateDel = "fa-save";
            } 
            else { 
                butState = "fa-save"; 
                butStateDel = "fa-edit";
            }
            
            curPut.prop('disabled',inputState);

            curBC.addClass(butState);
            curBC.removeClass(butStateDel);
            
        });
        
    }

    const buts = $('.settings-form > .form-wrapper-special button'),
          butConts = $('.settings-form > .form-wrapper-special button i.far'),
          puts = $('.settings-form > .form-wrapper-special .input');

    for (let index = 0; index < $('.settings-form > .form-wrapper-special').length; index++) {
        buts.eq(index).click(function (e,t) { 

            const curPuts = puts.eq(index),
                  curBCs = butConts.eq(index);
            let inputStates = false,
                butStates = "",
                butStateDels = "",
                attrState = 'text';

            if (!curPuts.prop('disabled')) {
                inputStates = true;
                butStates = "fa-edit";
                butStateDels = "fa-save";

                if(index === 1){ attrState = 'password'; }
                else{ attrState = 'email'; }
            } 
            else { 
                butStates = "fa-save"; 
                butStateDels = "fa-edit";
            }
            
            curPuts.prop('disabled',inputStates);
            curPuts.attr('type',attrState);

            curBCs.addClass(butStates);
            curBCs.removeClass(butStateDels);
            
        });
        
    }

    
});
