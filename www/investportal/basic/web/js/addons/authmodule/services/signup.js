async function SignUpService(){
    let isOpenedStep = [
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="2"]').css('display') != 'none',
        $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="3"]').css('display') != 'none'
    ];
    

    var errorMess = "",
        validData = [],
        notEmpty = [];
    
    if(isOpenedStep[1]){
        notEmpty = [
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(0).val() !== '',
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(1).val() !== '',
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(2).val() !== '',
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(3).val() !== ''
        ];

        validData = [
            /^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(0).val()),
            /^[a-zA-Z0-9_]+$/.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(1).val()),
            /^[a-zA-Z0-9_]+$/.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(2).val()),
            /^([+]?[0-9\s-\(\)]{3,25})*$/i.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(3).val())
        ];
        var isValidPassed = $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(2).val() === $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(1).val();

        if((notEmpty[0] && notEmpty[1] && notEmpty[2] && notEmpty[3]) && isValidPassed && (validData[0] && validData[1] && validData[2] && validData[3])){
            OpenStep('SignUp', 2);
        }
        else{
                if(!notEmpty[0]){
                    errorMess += "Email field this is required\n\n";
                }
                else if(!validData[0]){
                    errorMess += "Input valid email, please!(Example: john@gmail.com)\n\n";
                }

                if(!notEmpty[1]){
                    errorMess += "Password field this is required\n\n";
                }

                if(!isValidPassed){
                    errorMess += "Passwords don't match\n\n";
                }
                else if(!notEmpty[2]){
                    errorMess += "Confirm password field this is required\n\n";
                }

                if(!notEmpty[3]){
                    errorMess += "Phone field this is required\n\n";
                }
                else if(!validData[3]){
                    errorMess += "Input valid phone, please!(Example: +1 (012) 345-67-89)\n\n";
                }
        }
    }
    else if(isOpenedStep[2]){
       OpenStep('SignUp', 3);

       var q = { phone: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(3).val() },
		   s = new CodeSender('SignUp').send(q);

	   if(s === 'OK'){ console.log('SMS Send code success!'); }
	   else{
		   do{
			 var problem = alert('The code could not be sent! By clicking "OK", try to repeat this procedure.');

			 if(!problem){ var retry = s; }

		   } while(retry === 'OK' && console.log('SMS Send code success!'));
	   }

       $('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').html('Sign up');
    }
    else if(isOpenedStep[3]){
		const inputedCode = $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="3"] form div input').val();
		var isValidCode = new CodeSender('SignUp').valid({
			phone: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(3).val(),
			code: inputedCode
		});
        if(inputedCode.length === 0 || inputedCode.length < 4 || inputedCode.length > 4){
            alert('The entered code must have a four-digit format, or it is not entered!');
        }
        else if(isValidCode !== 'OK'){
             if(isValidCode === 'Error'){ alert('The code is entered incorrectly and check it carefully, please!'); }
             else{
				  do{
					 var problem = alert('The code could not be sent! By clicking "OK", try to repeat this procedure.');

					 if(!problem){ var retry = isValidCode; }

				  } while(retry === 'OK' && console.log('SMS Code verify success!'));
			 }
        }
        else{
			var phoneNumber = $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(3).val();
		
			if(phoneNumber.indexOf('+')){ phoneNumber += phoneNumber.substr(-2,0); }
			if(phoneNumber.indexOf('(') && phoneNumber.indexOf(')') && phoneNumber.indexOf('-')){ phoneNumber += phoneNumber.replace(/\D/g, ''); }
			if(phoneNumber.indexOf('8')){ phoneNumber += phoneNumber.substr(-1,0); }
		
			var UpQuery = {
				rsq: 'DefaultService',
				rsqt: {
					fn: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(0).val(),
					sn: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(1).val(),
					login: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(2).val(),
					password: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(1).val(),
					email: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div input').eq(0).val(),
					phone: phoneNumber,
					country: $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="1"] form div select').val(),
					isAccept: true
				}
			};

			var responseUp = await fetch('/accounts/signUp', {
				method: 'POST',
				body: {'serviceQuery': JSON.stringify(UpQuery)}
			});

			switch(responseUp.status){
				case 202:
					$('.module-page[data-screen="SignUp"] > main #reg-content li form div input').val('');
					$('#auth-lightbox > .close').trigger('click');
					OpenStep('SignUp', 0);

					let finishReg = alert('Congratulations! You have successfully created an account on our portal and for you we have expanded the possibilities of using our services ;-)');

					console.log('SMS Code verify success!');
					if(!finishReg){
						AutoSignIn(UpQuery.rsqt.login);
						location.reload(true);
					}
				break;
				case 400:
					var errors = await JSON.parse(responseUp.json());
					var eMess = '';

					for(let id in errors){ eMess += errors[id].validError + '\n'; }

					alert(eMess);
					OpenStep('SignUp', 0);
				break;
				default:
					 do{
						var problem = alert('The new account data not be sent! By clicking "OK", try to repeat this procedure.');

						if(!problem){ var retry = responseUp; }

					 } while(retry.status === 202 && $('.module-page[data-screen="SignUp"] > main #reg-content li form div input').val('') && $('#auth-lightbox > .close').trigger('click') && OpenStep('SignIn', 0) && AutoSignIn(UpQuery.rsqt.login) && location.reload(true));
				break;
			}
        }
    }
    else{
        notEmpty = [
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(0).val() !== '',
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(1).val() !== '',
            $('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(2).val() !== ''
        ];

        validData = [
            /^[a-zA-Z]+$/.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(0).val()),
            /^[a-zA-Z]+$/.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(1).val()),
            /^[a-zA-Z0-9_.]{1,30}$/.test($('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(2).val())
        ];
        if((notEmpty[0] && notEmpty[1] && notEmpty[2]) && (validData[0] && validData[1] && validData[2])){
            OpenStep('SignUp', 1);
        }
        else{
            if(!notEmpty[0]){
                    errorMess += "First name field this is required\n\n";
            }
            else if(!validData[0]){
                    errorMess += "Input valid first name in English, please!(Example: John)\n\n";
            }

            if(!notEmpty[1]){
                    errorMess += "Surname field this is required\n\n";
            }
            else if(!validData[1]){
                    errorMess += "Input valid surname in English, please!(Example: Johnson)\n\n";
            }

            if(!notEmpty[2]){
                    errorMess += "Login field this is required\n\n";
            }
            else if(!validData[2]){
                    errorMess += "Input valid login, please!(Example: investportal2021)\n\n";
            }
            

        }
        
    }

    if(errorMess){
        alert(errorMess);
    }


}

const AutoSignIn = async function(login) {
	var autoInQuery = {fsq:{portalId:login}};
	var response_autoIn = await fetch('/accounts/autoAuth', {
				method: 'POST',
				body: {'serviceQuery': JSON.stringify(autoInQuery)}
	});

	switch(response_autoIn.status){
				case 202: console.log('Automatic authorization success!'); break;
				default:
				   do{
					 var problem = alert('There was a failure in the automatic authorization service! By clicking "OK", try to repeat this procedure.');

					 if(!problem){ var retry = response_autoIn.status; }

				   } while(retry === 202 && console.log('Automatic authorization success!'));
				break;
	}
}
const SignUpService = () => { $('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').click(SignUpFormProcess);  }
