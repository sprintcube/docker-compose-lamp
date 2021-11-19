class FacebookDataAccess{
    constructor(service){
        this.service = service;
        
        let query = new URLSearchParams({
		  client_id : '404988774385568',
		  redirect_uri : '',
		  scope : '',
		  response_type : 'fbToken',
		  state : '123'
		});
        
        this.fbAuth = 'https://www.facebook.com/dialog/oauth' + query.toString();
    }

    proccess(query){
        var isSIN = this.service === 'SignIn',
            isSUP = this.service === 'SignUp';
            
        
        
		
		
		if(isSIN){
			
		}
		
		if(isSUP){
			
		}
        
    }
    activate(dataQuery){
        var isSIN = this.service === 'SignIn',
            isSUP = this.service === 'SignUp';
            
        if(isSIN){
			
		}
		
		if(isSUP){
			
		}
    }
}
