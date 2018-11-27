// data-model object with methods to feed itself with JSON to collect data
const dt = (()=>{
  const dt = {}

  const field_list = ['id', 'name', 'category', 'details', 'deadline',
                      'dt_create', 'dt_modify'];

  dt.get_whatever = function get_data_from_db_to_dm() {
    f.GET('PHP/output.php', response_json => {
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  dt.get_static_fields = function get_static_fields_from_db_to_dm() {
    const fields = ['name', 'details'];
    f.GET('PHP/output.php?fields='+JSON.stringify(fields), response_json => {
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  dt.get_fields = function get_certain_fields_from_db_to_dm(...fields) {
    fields = fields.map(field => {
      if (typeof(field)=='string' && field_list.includes(field)) return field;
      else if (typeof(field)=='number' && field<field_list.length)
        return field_list[field];
      else throw 'no such field in field_list';
    })

    if (fields) f.GET('PHP/output.php?fields='+JSON.stringify(fields),
                      response_json => {
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  return dt;
})();
