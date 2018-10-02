/*
Если есть cookie, отправить его в usercheck.php и получить ответ,
если нету, считать, что ответ false.

  Если ответ false:
    Если страница не login или register то редирект на login.
  иначе Если ответ true:
    Если страница login или register то предложить выйти

Варианты обстоятельств:
  Страницы внутренние
  Страницы внешние

Варианты ситуации:
  Куки user нет
    if Страницы внутренние:
      Переадресовать на login.htm

  Кука user есть
    if Страницы внутренние:
      Запросить проверку куки, передав соответствующий колбэк, готовый принять любое из трёх возможных условий.
    else:
      Запросить проверку куки, передав другой соответствующий колбэк, готовый принять любое из трёх возможных условий.

Варианты условий:
  Проверка пройдена успешно
  Проверка пройдена провально
  Проверка не получилась (ответ не получен или ответ не один из ожидаемых)

Варианты действий:
  Сказать, что logged in и предложить выйти
    ДА - Выполнить logout
    НЕТ - Переадресовать на inside.htm

*/

  // function to Check On Load and Act accordingly
f.COLA = function check_on_load_and_act_accord() {
  var inside = (location.pathname != "/StackTech1/login.htm" &&
                location.pathname != "/StackTech1/register.htm");
  var user = f.APIcookie.get('user');
  if (user===undefined) {
    if (inside) location.replace('login.htm');
  }
  else {
    const incb = function inside_callback(response) {
      if (response == 'invalid') {
        f.APIcookie.remove('user');
        location.replace('login.htm');
      }
      else if (response !== 'valid') alert(response);
    }

    const outcb = function outside_callback(response) {

/*
      if (response == 'valid') {
        var prompt = 'You are already logged in. ' +
                     'Would you like to log out now and proceed to ' +
                     location.pathname;
        if (!confirm(prompt)) location.replace('inside.htm');
        else {
          f.POST('PHP/logout.php?cookie='+user);
          f.APIcookie.remove('user');
        }
      }
      else if (response == 'invalid') {
        f.POST('PHP/logout.php?cookie='+user);
        f.APIcookie.remove('user');
      }
*/

      var prompt = 'You are already logged in. ' +
                   'Would you like to log out now and proceed to ' +
                   location.pathname;
      if (response == 'invalid' || (response == 'valid' && confirm(prompt))) {
        f.POST('PHP/logout.php?cookie='+user);
        f.APIcookie.remove('user');
      }
      else if (response == 'valid') location.replace('inside.htm');
    }
    const reportcb = response => alert(response);
    f.POST('PHP/cookiecheck.php?cookie='+user, inside? incb : outcb, reportcb);
  }
}

/*
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
*/

f.COLA();
