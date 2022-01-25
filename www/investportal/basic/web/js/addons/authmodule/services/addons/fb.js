function randomSymbol(){
	let result;
	
	let source = ['!', '@', '_', '-', '.'];
	let data = Math.floor(Math.random() * source.length);
	
	result = source[data];
	
	return result;
}

function randomIPPoint(){ return Math.floor(Math.random() * 3); }

function getFacebookPortalUri(){
	let getDomain = window.location.hostname, u;
	
	switch(getDomain){
		case 'zolotaryow.aplex.ru': u = 'http://zolotaryow.aplex.ru/investportal/'; break;
		default: u = 'http://investportal.aplex/'; break;
	}
	
	return u + '/accounts/fb';
}

function currentUserIp(){
	let result;
	fetch('http://ip-api.com/json').then(response => { result = response.json(); });
	
	return result.query;
}

class FacebookDataAccess{
    constructor(service){
        this.service = service;
        
        let query = new URLSearchParams({
		  client_id : '404988774385568',
		  redirect_uri : getFacebookPortalUri(),
		  scope : 'email',
		  response_type : 'svcCodeQuery',
		  state : 'web'
		});
        
        this.fbAuth = 'https://www.facebook.com/dialog/oauth' + query.toString();
    }

    proccess(){
        var isSIN = this.service === 'SignIn',
            isSUP = this.service === 'SignUp';
            
        let facebookData = JSON.parse(get_cookie('fbService'));
           	
		
		if(isSIN){
			switch(facebookData['resState']){
				case 0:
					//Для полной авторизации
					
					let userData = facebookData['uid'];
					
					if(userData['authValid']){
						let userData = facebookData['uid'];
					
						if(userData['isUserSignUped']){
							userData = userData['login'];
							
							let authQuery = {
								serviceQuery: {
									fsq: [ portalId: userData ]
								}
							};
							
							fetch('/accounts/autoAuth', {
								method: 'POST',
								body: authQuery
							}).then(response => {
								if(response.status != 405){ location.reload(true); }
								else{ alert('Login error!'); }
							}).catch(error => {
								alert('Response error!');
							});
						}
					}
				break;
				default:
					
				break;
			}
		}
		
		if(isSUP){
			switch(facebookData['resState']){
				case 0:
					//Для первичной регистрации
					
					let userData = facebookData['uid'];
					let windowOpen = $('.header > #header_bottom .hb_bottom header .header-informer footer .user-services a, .header > #header_bottom_adaptive header ul.adaptive-buttons li:nth-last-child(2)');
					
					if(userData['firstName'] && userData['secondName'] && userData['login']){
						let fields = [
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(0),
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(1),
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(2)
						],
							q = [
								userData['firstName'], userData['secondName'], userData['login']
							];
						
						for(let i = 0; i < q.length; i++){ fields[i].val(q); }
						
						$('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').trigger('click');
					}
					else{
						windowOpen.trigger('click');
					}
					
					
					if(userData['email'] && userData['phone']){
						var generatePass = Math.floor(Date.now() / 1000).substr(0, 8) + randomSymbol() + currentUserIp().split('.' || ':')[randomIPPoint()];
						let fields = [
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(0),
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div input').eq(3),
							$('.module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div:nth-child(2) input, .module-page[data-screen="SignUp"] main #reg-content li[data-signstep="0"] form div:nth-child(3) input')
						],
							q = [ userData['email'], userData['phone'], md5(generatePass) ];
							
						for(let i = 0; i < q.length; i++){ fields[i].val(q); }
						
						$('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').trigger('click');
						
					}
					else{
						windowOpen.trigger('click');
					}
					
					if(userData['region']){
						let fields = [$('')],
							q = [userData['region']];
							
						for(let i = 0; i < q.length; i++){ 
							let listData = fields[i];
							let country = q[i];
							
							for(let k = 0; listData.length; k++){
								let currentRegion = listData[i];
								
								if(country === currentRegion.val()){ currentRegion.prop('selected', 'selected'); }
							}
						}
						
						$('.module-page[data-screen="SignUp"] > main #reg-footer button#form-submit').trigger('click');
						windowOpen.trigger('click');
						
					}
					
					
				break;
				case 1:
					//Если пользователь зарегистрирован
					
					let userData = facebookData['uid'];
					
					if(userData['isUserSignUped']){
						userData = userData['login'];
						
						let authQuery = {
							serviceQuery: {
								fsq: [ portalId: userData ]
							}
						};
						
						fetch('/accounts/autoAuth', {
							method: 'POST',
							body: authQuery
						}).then(response => {
							if(response.status != 405){ location.reload(true); }
							else{ alert('Auth error!'); }
						}).catch(error => {
							alert('Response error!');
						});
					}
				break;
				default:
				
				break;
			}
		}
        
    }
    activate(){
        var isSIN = this.service === 'SignIn',
            isSUP = this.service === 'SignUp';
            
        let authWindow = null;
            
        if(isSIN){
			authWindow = window.open(this.fbAuth,
									'loginWindow',
                                   `toolbar=no,
                                    location=no,
                                    status=no,
                                    menubar=no,
                                    scrollbars=yes,
                                    resizable=yes,
                                    width=$(window).width(),
                                    height=$(window).height()`);
                                    
           authWindow.on('load', (e,t) => {
			   
		   });
           
           authWindow.on('focus', (e,t) => {
			   
		   });
		}
		
		if(isSUP){
			authWindow = window.open(this.fbAuth,
									'upWindow',
                                   `toolbar=no,
                                    location=no,
                                    status=no,
                                    menubar=no,
                                    scrollbars=yes,
                                    resizable=yes,
                                    width=$(window).width(),
                                    height=$(window).height() / 2`);
                                    
           authWindow.on('load', (e,t) => {
			   
		   });
           
           authWindow.on('focus', (e,t) => {
			   
		   });
		}
    }
}
