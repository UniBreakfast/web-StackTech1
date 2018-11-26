// data-model object with methods to feed itself with JSON to collect data
const dt = (()=>{
  const dt = {}

  dt.get_static = function get_data_from_db_to_dm() {
    f.GET('PHP/output.php', response_json =>{
      dm.eatJSON(response_json, true);
      console.log(dm);
    });
  }

  return dt;
})();
