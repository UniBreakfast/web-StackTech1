/*
Если есть cookie, отправить его в usercheck.php и получить ответ,
если нету, считать, что ответ false.

  Если ответ false:
    Если страница не login или register то редирект на login.
  иначе Если ответ true:
    Если страница login или register то предложить выйти

*/


var inside = (location.pathname != "/StackTech1/login.htm" &&
              location.pathname != "/StackTech1/register.htm");
function act_on_check(response) {
  if (response === 'valid' && inside)
}
