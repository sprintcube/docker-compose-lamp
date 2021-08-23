function ForgotFormProcess(e,t){
    let isOpenedStep = [
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"]').css('display') != 'none',
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"]').css('display') != 'none',
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"]').css('display') != 'none'
    ];

    if(isOpenedStep[1]){
        OpenStep('Forgot', 2);
    }
    else if(isOpenedStep[2]){
        $('.module-page[data-screen="Forgot"] > main #reg-content li form div input').val('');
        $('#auth-lightbox > .close').trigger('click');
        OpenStep('Forgot', 0);
    }
    else{
        OpenStep('Forgot', 1);
    }
}


const ForgotPassService = () => {
    $('.module-page[data-screen="Forgot"] > main #reg-footer button#form-submit').click(ForgotFormProcess);  
}