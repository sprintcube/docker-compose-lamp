class CodeSender{

    constructor(service){
        this.service = service;
    }

    send(query){
        var isSUP = this.service === 'SignUp',
            isRST = this.service === 'Forgot';

        var generateCode = [
            Math.ceil(getRandomFromRange(1000,9999)),
            Math.ceil(getRandomFromRange(2000,4600))
        ];

        var saveCode, message = "";


        if(isRST){
            message += " - Restore your account access code";
            saveCode = generateCode[1];
        }
        else if(isSUP){
            message += " - Your account registration confirm code";
            saveCode = generateCode[0];
        }



        set_cookie("serviceCode",saveCode);

        alert("Message by Investportal to "+ query +":\n\n" + get_cookie("serviceCode") + message + " The code is valid for five minutes;-)");
  
    }

    valid(formQuery){
        var isSUP = this.service === 'SignUp',
            isRST = this.service === 'Forgot';

            if(isRST){
                if(formQuery === get_cookie("serviceCode")){
                    return 'RST_Success';
                }
                else{
                    return 'Fail';
                }
            }
            else if(isSUP){
                if(isRST){
                    if(formQuery === get_cookie("serviceCode")){
                        return 'SUP_Success';
                    }
                    else{
                        return 'Fail';
                    }
                }
            }

        
    }
}