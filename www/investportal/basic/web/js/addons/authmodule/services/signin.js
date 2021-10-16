
function SignInFormProcess() {

    var notEmpty = [
        $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val() !== '',
        $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(1).val() !== ''
    ],
        isValid = /^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val()) || /^[a-zA-Z0-9_.]{1,30}$/.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val()) || /^([+]?[0-9\s-\(\)]{3,25})*$/i.test($('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val());
        errorMessage = "";

        
    if ((notEmpty[0] && notEmpty[1]) && isValid) {
		var ld = $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(0).val();

		if(/^([+]?[0-9\s-\(\)]{3,25})*$/i.test(ld)){
				if(ld.indexOf('+')){ ld += ld.substr(-2,0); }
				if(ld.indexOf('(') && ld.indexOf(')') && ld.indexOf('-')){ ld += ld.replace(/\D/g, ''); }
				if(ld.indexOf('8')){ ld += ld.substr(-1,0); }
		}



	    var InQuery = {
				rsq: 'DefaultService',
				rsqt: {
					portalId: ld,
					password: $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').eq(1).val(),
				}
		};

		var responseIn = await fetch('/accounts/signIn', {
				method: 'POST',
				body: {'serviceQuery': JSON.stringify(InQuery)}
		});

		switch(responseUp.status){
				case 202:
					$('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').val('');
					$('#auth-lightbox > .close').trigger('click');

					location.reload(true);
				break;
				case 400:
					var errors = await JSON.parse(responseIn.json());
					var eMess = '';

					for(let id in errors){ eMess += errors[id].validError + '\n'; }

					alert(eMess);
				break;
				default:
					 do{
						var problem = alert('Authorization is temporarily unavailable! By clicking "OK", try to repeat this procedure.');

						if(!problem){ var retry = responseIn; }

					 } while(retry.status === 202 && $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form form input').val('') && $('#auth-lightbox > .close').trigger('click') && location.reload(true));
				break;
		}
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

const SignInService = () => { $('#auth-lightbox > .module-page[data-screen="SignIn"] main .module-form button#form-submit:nth-last-child(1)').click(SignInFormProcess); }
