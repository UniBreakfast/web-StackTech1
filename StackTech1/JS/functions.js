  // functions container object
const f = {
  request: function request(type, url, callback, falldata, fallcb) {
    const request = new XMLHttpRequest();
    request.open(type, url, true);
    request.onload = () => {
      if (callback) {
        if (request.status >= 200 && request.status < 400 && !request.response.startsWith('<?php')) {
          if (request.response !== '') callback(request.response);
          else callback();
        }
        else falldata ? callback(falldata) : callback();
      }
      else if (fallcb) falldata ? fallcb(falldata) : fallcb();
    }
    request.send();
  }
}

f.GET  = f.request.bind(this, 'GET');
f.POST = f.request.bind(this, 'POST');
