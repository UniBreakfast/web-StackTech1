// data-model object with methods to feed itself with JSON to collect data
const dt = (()=>{
  const dt = {}

  dt.get_whatever = function get_data_from_db_to_dm() {
    f.GET('PHP/output.php', response_json =>{
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  dt.get_static_fields = function get_static_fields_from_db_to_dm() {
    const fields = ['name', 'details'];
    f.GET('PHP/output.php?fields='+JSON.stringify(fields), response_json =>{
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  return dt;
})();
