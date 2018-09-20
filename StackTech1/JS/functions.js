  // functions container object
const f = {
  request: function request(type, url, callback, falldata, fallcb) {
    const request = new XMLHttpRequest();
    request.open(type, url, true);
    request.onload = () => {
      if (request.status >= 200 && request.status < 400 && !request.response.startsWith('<?php'))
        callback(request.response);
      else fallcb? fallcb(falldata) : callback(falldata);
    }
    request.send();
  }
}

f.GET  = f.request.bind(this, 'GET');
f.POST = f.request.bind(this, 'POST');
