// data-model object with methods to feed itself with JSON to collect data
const dt = (()=>{
  const dt = {}

  let route = 'PHP/dt.php';
  let table = 'test_endeavors';
  let field_list = ['id', 'name', 'category', 'details', 'deadline',
                    'dt_create', 'dt_modify'];

  dt.setRoute = function setRoute(name, new_route, new_table, new_field_list) {
    if (new_route) route = new_route;
    if (new_table) table = new_table;
    if (new_field_list) field_list = new_field_list;
  }

/*
  dt.get_whatever = function get_data_from_db_to_dm() {
    f.GET('PHP/dt.php', response_json => {
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  dt.get_static_fields = function get_static_fields_from_db_to_dm() {
    const fields = ['name', 'details'];
    f.GET(route+'?table='+table+'&fields='+JSON.stringify(fields),
          response_json => {
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }
*/

  dt.getFields = function get_certain_fields_from_db_to_dm(...fields) {}

  dt.askFields = function ask_for_certain_fields_from_db_to_dm(...fields) {
    let userid = f.cookie.get('userid'), token = f.cookie.get('token');
    if (userid && token) {
      if (fields.length) fields = fields.map(field => {
        if (typeof(field)=='string' && field_list.includes(field)) return field;
        else if (typeof(field)=='number' && field<field_list.length)
          return field_list[field];
        else throw 'no such field in field_list';
      });
      else fields = field_list;

      if (fields.length)
        f.GET(`PHP/dt.php?userid=${userid}&token=${token}&table=${table}&fields=`
              +JSON.stringify(fields),
          response => {
          if (response.endsWith('}')) {
            f.cookie.set('token', response.substr(0,32), 2.5);
            dm.eatJSON(response.substr(32), true);
            log(dm);
          }
          else if (!response) log('response is empty');
          else log(response);
        }, log);
    }
    else log('you are not even signed in');
  }

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
