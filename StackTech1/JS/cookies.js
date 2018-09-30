;
window.Cookies = (function () {
  function extend () {
    var result = {};
    for (var i = 0; i < arguments.length; i++) {
      var attributes = arguments[i];
      for (var key in attributes) result[key] = attributes[key];
    }
    return result;
  }

  function decode (s) {
    return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
  }

  var api = {};

  function set (key, value, attributes) {
    attributes = extend({path: '/'}, api.defaults, attributes);

    if (typeof attributes.expires === 'number')
      attributes.expires = new Date(new Date()*1 + attributes.expires * 864e+5);

    attributes.expires=attributes.expires? attributes.expires.toUTCString() : '';

    try {
      var result = JSON.stringify(value);
      if (/^[\{\[]/.test(result)) value = result;
    } catch (e) {}

    value = encodeURIComponent(String(value))
      .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

    key = encodeURIComponent(String(key))
      .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
      .replace(/[\(\)]/g, escape);

    var stringifiedAttributes = '';
    for (var attributeName in attributes) {
      if (!attributes[attributeName]) continue;

      stringifiedAttributes += '; ' + attributeName;
      if (attributes[attributeName] === true) continue;

      stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
    }
    return (document.cookie = key + '=' + value + stringifiedAttributes);
  }

  function get (key, json) {
    var jar = {};
    var cookies = document.cookie ? document.cookie.split('; ') : [];
    for (var i = 0; i < cookies.length; i++) {
      var parts = cookies[i].split('=');
      var cookie = parts.slice(1).join('=');

      if (!json && cookie.charAt(0) === '"') cookie = cookie.slice(1, -1);

      try {
        var name = decode(parts[0]);
        cookie = decode(cookie);

        if (json) cookie = JSON.parse(cookie);
        jar[name] = cookie;

        if (key === name) break;
      } catch (e) {}
    }
    return key ? jar[key] : jar;
  }

  api.defaults = {};
  api.set = set;
  api.get     = function (key) { return get(key, false); };
  api.getJSON = function (key) { return get(key, true);  };
  api.remove  = function (key, attributes) {
    set(key, '', extend(attributes, { expires: -1 }));
  };
  return api;
})();
