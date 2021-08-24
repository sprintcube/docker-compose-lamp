
function SignFormProcess(e,t){
    $('.module-page[data-screen="SignIn"] > main .module-form form div input').val('');
    $('#auth-lightbox > .close').trigger('click');
}




const SignInService = () => {

    $('.module-page[data-screen="SignIn"] > main button#form-submit:nth-last-child(1)').click(SignFormProcess);  
}