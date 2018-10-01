/*
Если есть cookie, отправить его в usercheck.php и получить ответ,
если нету, считать, что ответ false.

  Если ответ false:
    Если страница не login или register то редирект на login.
  иначе Если ответ true:
    Если страница login или register то предложить выйти

Варианты I:
Куки есть





*/

f.act_on_check = function act_on_check(response) {
  var inside = (location.pathname != "/StackTech1/login.htm" &&
                location.pathname != "/StackTech1/register.htm");
  if (response === 'valid') {
    if (!inside && !confirm('You are already logged in. ' +
                            'Would you like to log out now and proceed to ' +
                            location.pathname)) location.href = 'inside.htm';
    }
  } else if (response === 'invalid') {
    // TODO delete cookie!
    if (inside) location.href = 'login.htm';
  } else {
    alert('No response from server. Something isn\'t working');
    location.href = 'auth.htm';
  }
}

if (!document.cookie) location.href = 'login.htm';
else f.POST('PHP/cookiecheck.php?cookie='+document.cookie, f.act_on_check);
