'use strict';

// data-model object with methods to feed itself with JSON to collect data
const clerk = (()=>{

  var clerk_php = 'Clerk/PHP/clerk.php';

  // 'Clerk/PHP/clerk.php' is set by default
  function setPath(path_to_clerk_php) { clerk_php = path_to_clerk_php }

  function SignUp(login, pass) {
    if (login && pass)
      f.POST(clerk_php+'?task=reg'+'&login='+login+'&pass='+pass,
             response => log(JSON.parse(response)), log);
    else log(new Response(102, 'E', 'Not enough credentials to register!'));
  }

  function SignIn(login, pass) {
    if (login && pass)
      f.POST(clerk_php+'?task=login'+'&login='+login+'&pass='+pass,
             response => {
        response = JSON.parse(response);      let d;
        if (d = response.data) {
          f.cookie.set('userid', d.userid, d.expire);
          f.cookie.set('token',  d.token,  d.expire);
        }
        log(response);
      }, log);
    else log(new Response(106, 'E', 'Not enough credentials to sign in!'));
  }

  function isSignedIn() {
    const userid = f.cookie.get('userid'), token = f.cookie.get('token');
    if (userid && token)
      f.POST(clerk_php+'?task=check'+'&userid='+userid+'&token='+token,
             response => {
        response = JSON.parse(response);      let d;
      if (d = response.data) {
        f.cookie.set('userid', userid,  d.expire);
        f.cookie.set('token',  d.token, d.expire);
      }
      log(response);
    }, log);
    else log(new Response(109, 'F', 'No complete session cookie found'));
  }

  function SignOut() {
    const userid = f.cookie.get('userid'), token = f.cookie.get('token');
    if (userid && token) {
      f.POST(clerk_php+'?task=logout'+'&userid='+userid+'&token='+token, 0,log);
      f.cookie.remove('userid');
      f.cookie.remove('token');
      log(new Response(111, 'S', 'Signed out'));
    }
    else log(new Response(113, 'I', 'You are not signed in!'));
  }

  function ChangePassword(login, oldpass, newpass) {
    if (login && oldpass && newpass)
      f.POST(clerk_php+'?task=newpass'+
             '&login='+login+'&oldpass='+oldpass+'&newpass='+newpass,
             response => log(JSON.parse(response), log));
    else log(new Response(117, 'E',
                          'Not enough credentials to change password!'));
  }

  function ChangeLogin(oldlogin, pass, newlogin) {
    if (oldlogin && pass && newlogin)
      f.POST(clerk_php+'?task=rename'+
             '&oldlogin='+oldlogin+'&pass='+pass+'&newlogin='+newlogin,
             response => log(JSON.parse(response), log));
    else log(new Response(121, 'E',
                          'Not enough credentials to change login!'));
  }

  function UnRegister(login, pass) {
    if (login && pass)
      f.POST(clerk_php+'?task=unreg'+'&login='+login+'&pass='+pass,
             response => log(JSON.parse(response), log));
    else log(new Response(125, 'E', 'Not enough credentials to unregister!'));
  }



  const dt = {setPath,
              SignUp, SignIn, isSignedIn, SignOut,
              ChangePassword, ChangeLogin, UnRegister}

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
