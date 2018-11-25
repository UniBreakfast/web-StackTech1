// data-model object with methods to feed itself with JSON to collect data
const dm = (()=>{
  const dm = {}

  class DBoid {}
  class Tempoid {}

  dm.classBuilder = function classBuilder(className, properties) {
    let classDef = `dm["${className}"] = class ${className} extends DBoid {`;
    if (properties) {
      classDef += 'constructor('+properties.join()+'){super();'
      properties.forEach(property =>
                         classDef += `this.${property} = ${property};`);
      classDef += '}';
    }
    classDef += '}';
    eval(classDef);
  }

  dm.eatJSON = (json, keep) => {
    let obj = JSON.parse(json);
    for (let [table, data] of Object.entries(obj)) {
      dm[table] = [];
      dm.classBuilder(data.class, data.headers);
      data.rows.forEach(row => dm[table].push(new dm[data.class](...row)));
    }
    if (keep) dm.lastJSON = json;
    else      dm.lastJSON = null;
  }

  return dm;
})();
