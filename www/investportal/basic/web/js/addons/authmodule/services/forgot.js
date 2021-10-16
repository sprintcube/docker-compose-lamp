function ForgotFormProcess(){
    let isOpenedStep = [
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"]').css('display') != 'none',
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"]').css('display') != 'none',
        $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"]').css('display') != 'none'
    ];

    var errorMess = "",
        validData = [
            /^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9]{1}[-0-9\.]{1,}[0-9]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u.test($('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val()) || /^[a-zA-Z0-9_.]{1,30}$/.test($('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val()) || /^([+]?[0-9\s-\(\)]{3,25})*$/i.test($('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val()),
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"] form div input').val().length === 0 || $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"] form div input').val().length < 4 ||  $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"] form div input').val().length > 4,
            /^[a-zA-Z0-9_]+$/.test($('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(0).val()),
            /^[a-zA-Z0-9_]+$/.test($('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(1).val()),
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(0).val() === $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(1).val()
        ],
        notEmpty = [
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val() !== '',
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"] form div input').val() !== '',
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(0).val() !== '',
            $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(1).val() !== ''
        ];

        var isValidCode = new CodeSender('Forgot').valid({
			login: $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val(),
			code: $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="1"] form div input').val()
		});

    
    if(isOpenedStep[1]){
        if(notEmpty[1] && !validData[1] && isValidCode === 'OK'){
            OpenStep('Forgot', 2);
            $('.module-page[data-screen="Forgot"] > main #reg-footer button#form-submit').html('Restore');
            console.log('SMS Code verify success!');
        }
        else{

            if(validData[1]){
                errorMess += "The entered code must have a four-digit format, or it is not entered!";
            }
            else if(isValidCode === 'Error'){
                errorMess += "The code is entered incorrectly and check it carefully, please!";
            }
            else {
                do{
				 var problem = alert('The code could not be sent! By clicking "OK", try to repeat this procedure.');

				 if(!problem){ var retry = isValidCode; }

			   } while(retry === 'OK' && console.log('SMS Code verify success!'));
            }

        }
    }
    else if(isOpenedStep[2]){

        if((notEmpty[2] && notEmpty[3]) && validData[5]){

			var ForgotQuery = {
				fsq: {
					portalId: $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val(),
					password: $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="2"] form div input').eq(0).val(),
				}
			};

			var responseForgot = await fetch('/accounts/forgot', {
				method: 'POST',
				body: {'serviceQuery': JSON.stringify(ForgotQuery)}
			});

			switch(responseForgot.status){
				case 202:
					$('.module-page[data-screen="Forgot"] > main #reg-content li form div input').val('');
					$('#auth-lightbox > .close').trigger('click');
					OpenStep('SignIn', 0);

					let finishRes = alert('You have successfully restored access to your portal account. After a while, you will be logged in to the portal services, but do not forget to save the access data when logging in anywhere and make sure that only you know about them;-)');

					var ld = ForgotQuery.fsq.portalId;

					if(/^([+]?[0-9\s-\(\)]{3,25})*$/i.test(ld)){
						if(ld.indexOf('+')){ ld += ld.substr(-2,0); }
						if(ld.indexOf('(') && ld.indexOf(')') && ld.indexOf('-')){ ld += ld.replace(/\D/g, ''); }
						if(ld.indexOf('8')){ ld += ld.substr(-1,0); }
					}
					if(!finishRes){
						location.reload(true);
						AutoSignIn(ld);
					}
				break;
				case 400:
					var errors = await JSON.parse(responseForgot.json());
					var eMess = '';

					for(let id in errors){ eMess += errors[id].validError + '\n'; }

					alert(eMess);
				break;
				default:
					 do{
						var problem = alert('The access recovery service is temporarily unavailable! By clicking "OK", try to repeat this procedure.');

						if(!problem){ var retry = responseForgot; }

					 } while(retry.status === 202 && $('.module-page[data-screen="Forgot"] > main #reg-content li form div input').val('') && $('#auth-lightbox > .close').trigger('click') && OpenStep('SignIn', 0) && AutoSignIn(UpQuery.rsqt.login) && location.reload(true));
				break;
			}
        }
        else{
            if(!notEmpty[2]){
                errorMess += "Password field this is required\n\n";
            }

            if(!notEmpty[3]){
                errorMess += "Confirm password field this is required\n\n";
            }

            if(!validData[5]){
                errorMess += "Passwords don't match\n\n";
            }
        }
    }
    else{
        if(notEmpty[0] && validData[0]){
            OpenStep('Forgot', 1);

             var q = { phone: $('.module-page[data-screen="Forgot"] main #reg-content li[data-signstep="0"] form div input').val() },
				 s = new CodeSender('Forgot').send(q);

			 if(s === 'OK'){ console.log('SMS Send code success!'); }
			 else{
				   do{
					 var problem = alert('The code could not be sent! By clicking "OK", try to repeat this procedure.');

					 if(!problem){ var retry = s; }

				   } while(retry === 'OK' && console.log('SMS Send code success!'));
			  }
            
        }
        else{
            if(!notEmpty[0]){
                errorMess += "Account data field is required!\n\n";
            }
            else if(!validData[0]){
                errorMess += "Input valid account data, please!(Examples: john@gmail.com, investportal2021 or +1 (012) 345-67-89)\n\n";
            }
        }
    }

    if(errorMess){
        alert(errorMess);
    }
}

const AutoSignIn = (login) => {
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
const ForgotPassService = () => { $('.module-page[data-screen="Forgot"] > main #reg-footer button#form-submit').click(ForgotFormProcess); }
