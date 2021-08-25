
function SignInFormProcess() {

    var notEmpty = [
        $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val() !== '',
        $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(1).val() !== ''
    ],
        isValid = /^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val()) || /^[a-zA-Z0-9_.]{1,30}$/.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val()) || /^([+]?[0-9\s-\(\)]{3,25})*$/i.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val());
        errorMessage = "";

        
    if ((notEmpty[0] && notEmpty[1]) && isValid) {
        $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').val('');
        $('#auth-lightbox > .close').trigger('click');

        location.reload(true);
    }
    else {
        if (!isValid) {
            errorMessage += "Input valid account data, please!(Examples: john@gmail.com, investportal2021 or +1 (012) 345-67-89)\n\n";
        }

        if (!notEmpty[0]) {
            errorMessage += "Account data field is required!\n\n";
        }

        if (!notEmpty[1]) {
            errorMessage += "Password field is required!\n\n";
        }
    }

    if(errorMessage){
        alert(errorMessage);
    }
}




const SignInService = () => {

    $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form button#form-submit:nth-last-child(1)').click(SignInFormProcess);


}