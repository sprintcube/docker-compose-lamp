class AuthHeader extends React.Component{
  render(){
     let deviceIcon;
    
    if(device.mobile()){ deviceIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAAZUlEQVRYhe3WMQqDYBAFYRG9UZqofQ4bT2eKlJ+FVnaBxZ/AmwPsDCws23UhFIEFr5byD7543C2fsTlYMUYeeeSRV8qf54WDN4ZqR189sBxMzVaQiEQkIhE/RLR7Si8Rbd7y8NfsnwB75rKFonYAAAAASUVORK5CYII='; }
    else if(device.tablet()){ deviceIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACKklEQVRYhe2XsU8UQRTGvyWnhV44bDnpxMNYiHD2RGMr+HdYGo09aEsgJDYc+CcYrWTRhL8CJRZ6d602yFHBz2LfhnFzuzuzXnINL5lcMvN933sz7928HenSxmxRCBhoSVqR9FhSU9KMLfUk9SXFkt5HUXQ0yiAFtIHP+Ns+sDgKx1eALeDchH8B28AToAVct9ECloGOYTDOJlCr6vwG8MXEToBVYNKDNwmsAQPnNKaq7Dw98j7QrrCBe8AP0zgAroaQt4zYBaZDnTs600DPtDZ8SW3L3wlwvwA3Adzx0FuwdJwBCz4BpEe/WuK8A/wBljw0X5tmXAZsOdU+tOAc52lxPvQIoAH8Ns5sEfCVgbZz1iPgreP8UZlzh7tjvBdFoNhAy0PWgnee4T817l4R6MhAtzLzlXfuaKTp/VoEOjZQPcd5kGW06zZ97M5PZGMI3VmApb7OcxHAN4vydmZ+FCmYM/7hsKhS69rvPxdMFEVIeiZpR9I1SR9Ci9DR7OYigJcWZSdn/X/+hu+M97wINN6LyMD7BlwrwIRexW9M81MZVsAiSTMaUNA88G9GbeCUpBnNlwZgpE2LuAc0vUjDdZpctOP1EGLNSUUfeFDB+Tzw0zRiQj/NgCkniAFJS2148BqW81Pj7hH6SeaI1YANyx9WybvACsnFUrcxR9Jsdp1qPwPWg3eeE8hd4CP+FhcVsGuhD5NZXTxMZiTdtKWejfRh8j1E99LGan8BKvPalbbKvGkAAAAASUVORK5CYII='; }
    else{
      deviceIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACj0lEQVRYhcWXTW9NURSGn00jWgmatFVNjQgVBmqKxtdINf6Czx9gxB+Qxh9oxQATqRgyFUk/hkiEVisGqCA+gmByaR6Du+hVveeec3ula7jPu9b77rPXXnstWGZLRcBqE9AH7AG2AmsBga/AU2ACGE8p/WyoAHU7cA4YANYBj4FXwNuAdALdwE7gM3AbuJhSms4rpBpxh3pZ/aGOqifU9gx8u3pSHQufS2pHveS71Vn1kXqkDv9+dVJ9qfYWdR5Qv6vX1dVFySviNKsj6jf1aF6n3UF+QS2UpFXiJXUwRGT/iTjz2dj5kskXiLihvsjKISLhHqnNRQlyYFrUKXW4GmB7ZG6hhFPb1Afq+RzYgeDoWezjNXW0DvKH6hO1M6fPuHpl4WKT+kE9XoC8Pcin1a4CfqfUj1FV/yweUn9mJkgDyMO3I7j2A6yI9T3A45TS+zzkwB1gFXAgpfS6iICU0jtgCthbKWAz5dpehPxgSulNEfIKmwW2VApYD2QGU1c1iJzgWl8pYNnst4DPwMYsYEqpBBwGSsBdNRNfw7qAT5UCnlF+zzMtkrQRIrqDs2zqAXVO3ZDHW21V76kzdV7DObWvcnGl+l49WSBQXSLUM1H0mhZ+uKqO5Q0UPvWU4ol/SnF86ImHIl/j8LeI++rZHNhjakndVg1wKdqo//Ecr4k/NZQF6oimYeQ/NCQ31edqWy1wb7RPgw1syS5GzF15nY6Gww21ZQnkLbHzb2p/UefeOI4pdaAO8mNx5s9z73yRIO3qcNyO8Wgmqg4a6gb1dFy1kjpU68zzjmY9zI9mrcAk8JL50WwjsAnYQbnG36I8ms3Uil10OF0J7KPcwGyjPJwCfGF+OJ1IKc0Vibus9guqEqy+yWNuxgAAAABJRU5ErkJggg==';
    }
    
    return(
      <React.Fragment>
        <img src={deviceIcon} id="back" />
        <h2 id="title">Welcome to Investportal Admin Services!</h2>
      </React.Fragment>
    );
  }
}
class AuthBody extends React.Component{
  render(){
    
    return(
      <React.Fragment>
        <center><form action="/admin/auth" method="POST">
          <div for="username">
            <label>Username</label>
            <input type="text" name="username" />
          </div>
          <div for="pass">
            <label>Password</label>
            <input type="password" name="pass" />
          </div>
          <button type="button">Sign In</button>
          </form></center>
      </React.Fragment>
    );
  }
}
class AuthFooter extends React.Component{
  render(){
    return(
      <React.Fragment>
        <div class="copyright">
          &copy; Investportal. International Platform
for Investors and
Investment Projects 
        </div>
      </React.Fragment>
    );
  }
}

let cmps = [<AuthHeader />, <AuthBody />, <AuthFooter />],
    els = [document.querySelector('.admin-auth > header'),document.querySelector('.admin-auth > main'),document.querySelector('.admin-auth > footer')];

for(let i = 0; i < cmps.length; i++){ ReactDOM.render(cmps[i],els[i]); }

