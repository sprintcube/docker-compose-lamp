class CodeSender{

    constructor(service){
        this.service = service;
    }

    send(query){
        var isSUP = this.service === 'SignUp',
            isRST = this.service === 'Forgot';


        if(isRST){
			const login = query['login'];

			if(/^([+]?[0-9\s-\(\)]{3,25})*$/i.test(login)){
				if(login.indexOf('+')){ phone = login.substr(-2,0); }
				if(login.indexOf('(') && login.indexOf(')') && login.indexOf('-')){ phone = login.replace(/\D/g, ''); }
				if(login.indexOf('8')){ phone = login.substr(-1,0); }
			}
			
			let phone = this.__getAccountPhone(login);

			let svcQuery = {
				rsq: {
					service: 'Inbox',
					phone: phone
				}
			};
			
			var ws = this.CCService('forgot',svcQuery); //Code sender and generation service call

			if(ws === true){ return 'OK'; }
			else{ return 'Fail'; }
        }
        else if(isSUP){
			const phoneNumber = query['phone'];

			let phone = '';

			if(phoneNumber.indexOf('+')){ phone = phoneNumber.substr(-2,0); }
			if(phoneNumber.indexOf('(') && phoneNumber.indexOf(')') && phoneNumber.indexOf('-')){ phone = phoneNumber.replace(/\D/g, ''); }
			if(phoneNumber.indexOf('8')){ phone = phoneNumber.substr(-1,0); }

			let svcQuery = {
				rsq: {
					service: 'Inbox',
					phone: phone
				}
			};

			var ws = this.CCService('signUp',svcQuery);

			if(ws === true){ return 'OK'; }
			else{ return 'Fail'; }
        }

    }

    valid(formQuery){
        var isSUP = this.service === 'SignUp',
            isRST = this.service === 'Forgot';

            if(isRST){

			   const login = query['login'],
					 code = query['code'];

			   if(/^([+]?[0-9\s-\(\)]{3,25})*$/i.test(login)){
				if(login.indexOf('+')){ phone = login.substr(-2,0); }
				if(login.indexOf('(') && login.indexOf(')') && login.indexOf('-')){ phone = login.replace(/\D/g, ''); }
				if(login.indexOf('8')){ phone = login.substr(-1,0); }
			   }
			   
			   let phone = this.getAccountPhone(login);

			   let svcQuery = {
					rsq: {
						service: 'Valid',
						code: code,
						phone: phone
					}
				};	

               var ws = this.__CVCService('forgot',svcQuery); //Inputed code validation service call

               if(ws === true){ return 'OK'; }
               else if(ws === null){ return 'Fail'; }
			   else{ return 'Error'; }
            }
            else if(isSUP){
			   const login = query['phone'],
					 code = query['code'];
			   let phone = '';

			   if(phoneNumber.indexOf('+')){ phone = phoneNumber.substr(-2,0); }
			   if(phoneNumber.indexOf('(') && phoneNumber.indexOf(')') && phoneNumber.indexOf('-')){ phone = phoneNumber.replace(/\D/g, ''); }
			   if(phoneNumber.indexOf('8')){ phone = phoneNumber.substr(-1,0); }

			   let svcQuery = {
					rsq: {
						service: 'Valid',
						code: code,
						phone: phone
					}
				};	
				
               var ws = this.__CVCService('signUp',svcQuery);

               if(ws === true){ return 'OK'; }
               else if(ws === null){ return 'Fail'; }
			   else{ return 'Error'; }
            }

        
    }
     getAccountPhone(q){
		let sQ = {
			method: 'POST',
			body: {'serviceQuery': JSON.stringify(q) }
		},
			response =  fetch('/accounts/getInfo', sQ);


		if(response.status === 200){
			let data =  response.json();
			return data[0].phone;
		}
		else{ return null; }
		
	}
     __CCService(s,q){
		let sQ = {
			method: 'POST',
			body: {'serviceQuery': JSON.stringify(q) }
		},
			response =  fetch('/accounts/accept/' + s, sQ);

		if(response.status === 202){ return true; }
		else{ return null; }
	}
	 __CVCService(s,q){
		let sQ = {
			method: 'POST',
			body: {'serviceQuery': JSON.stringify(q) }
		},
			response =  fetch('accounts/accept/' + s, sQ);

		if(response.status === 202){ return true; }
		else if(response.status === 403){ return false; }
		else{ return null; }

			
	}
}
