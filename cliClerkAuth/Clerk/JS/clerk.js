// data-model object with methods to feed itself with JSON to collect data
const clerk = (()=>{

  function SignUp() {}

  function SignIn() {}

  function isSignedIn() {}

  function SignOut() {}

  function ChangePass() {}

  function ChangeLogin() {}

  function UnRegister() {}



  const dt = {SignUp, SignIn, isSignedIn, SignOut,
              ChangePass, ChangeLogin, UnRegister}

  // exchange right login and password for userid and a token (newly created)
  dt.signIn = function get_userId_and_token(login, password) {
    let userid = f.cookie.get('userid'), token = f.cookie.get('token');
    if (userid && token) {
      log ('previous signin cookie found, going to check');
      f.POST(`PHP/dt.php?task=usercheck&userid=${userid}&token=${token}`,
             response => {
        if (!response.startsWith('no ')) {
          f.cookie.set('userid', userid,  2.5);
          f.cookie.set('token', response, 2.5);
          log('you are already signed in');
        }
        else {
          log('previous cookie check failed, going to sign in');
          sign_in();
        }
      }, log);
    }
    else sign_in();

    function sign_in() {
      f.POST(`PHP/dt.php?task=signin&login=${login}&password=${password}`,
             response => {
        if (response.startsWith('[')) {
          [userid, token] = JSON.parse(response);
          f.cookie.set('userid', userid, 2.5);
          f.cookie.set( 'token',  token, 2.5);
          log('you are now signed in');
        }
        else {
          log(response);
        }
      });
    }
  }

  dt.signOut = function drop_current_session() {
    let userid = f.cookie.get('userid'), token = f.cookie.get('token');
    if (userid && token) {
      log ('cookie found, going to signout');
      f.POST(`PHP/dt.php?task=signout&userid=${userid}&token=${token}`,
             response => {
        if (response.startsWith('no%20')) log(response);
        else {
          f.cookie.remove('userid');
          f.cookie.remove('token');
          log(response);
        }
      }, log);
    }
    else log('you are not even signed in');
  }

  return dt;
})();
