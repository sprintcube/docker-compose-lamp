function SignFormProcess(e,t){
    let isOpenedStep = [
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="2"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="3"]').css('display') != 'none'
    ];

    if(isOpenedStep[1]){
       OpenStep('SignUp', 2);
    }
    else if(isOpenedStep[2]){
       OpenStep('SignUp', 3);
    }
    else if(isOpenedStep[3]){
        $('.module-page[data-screen="SignUp"] > main #reg-content li form div input').val('');
        $('#auth-lightbox > .close').trigger('click');
        OpenStep('SignUp', 0);
    }
    else{
        OpenStep('SignUp', 1);
    }


}


const SignUpService = () => {
    $('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').click(SignFormProcess);  
}