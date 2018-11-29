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

  dt.get_fields = function get_certain_fields_from_db_to_dm(...fields) {
    fields = fields.map(field => {
      if (typeof(field)=='string' && field_list.includes(field)) return field;
      else if (typeof(field)=='number' && field<field_list.length)
        return field_list[field];
      else throw 'no such field in field_list';
    })

    if (fields) f.GET(route+
                      '?table='+table+
                      '&fields='+JSON.stringify(fields)+
                      '&user='+f.cookie.get('user'),
                      response_json => {
      if (response_json.startsWith('{')) dm.eatJSON(response_json, true);
      else if (!response_json) console.log('response is empty');
      else console.log(response_json);
      console.log(dm);
    }, console.log);
  }

  return dt;
})();
