class FacebookDataAccess{
    constructor(service){
        this.service = service;
        
        let query = new URLSearchParams({
		  client_id : '404988774385568',
		  redirect_uri : '',
		  scope : 'email',
		  response_type : 'fbToken',
		  state : '123'
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
				
				break;
				default:
				
				break;
			}
		}
		
		if(isSUP){
			switch(facebookData['resState']){
				case 0:
				
				break;
				case 1:
				
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
