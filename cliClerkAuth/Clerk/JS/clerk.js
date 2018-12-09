// data-model object with methods to feed itself with JSON to collect data
const clerk = (()=>{

  var clerk_php = 'Clerk/PHP/clerk.php';

  // 'Clerk/PHP/clerk.php' is set by default
  function setPath(path_to_clerk_php) { clerk_php = path_to_clerk_php }

  function SignUp(login, pass) {
    if (login && pass)
      f.POST(clerk_php+'?task=reg'+'&login='+login+'&pass='+pass,
             response => log(JSON.parse(response)), log);
    else {
      let response = { msg: { type: 'ERROR', code: 102,
                              text: 'Not enough data to register!' } }
      log(response);
    }
  }

  function SignIn(login, pass) {
    if (login && pass)
      f.POST(clerk_php+'?task=login'+'&login='+login+'&pass='+pass,
             response => {
        response = JSON.parse(response);
        if (response.data) {
          f.cookie.set('userid', response.data.userid, response.data.expire);
          f.cookie.set('token',  response.data.token,  response.data.expire);
        }
        log(response);
      }, log);
    else {
      let response = { msg: { type: 'ERROR', code: 106,
                             text: 'Not enough data to sign in!' } }
      log(response);
    }
  }

  function isSignedIn() {}

  function SignOut() {}

  function ChangePass() {}

  function ChangeLogin() {}

  function UnRegister() {}



  const dt = {setPath,
              SignUp, SignIn, isSignedIn, SignOut,
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
