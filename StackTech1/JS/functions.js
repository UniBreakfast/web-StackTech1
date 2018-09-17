  // functions container object
const f = {
  request: function request(type, url, callback, fallback) {
    const request = new XMLHttpRequest();
    request.open(type, url, true);
    request.onload = () => {
      if (request.status >= 200 && request.status < 400) callback(request.responseText);
      else callback(fallback);
    }
    request.send();
  }
}

f.GET  = f.request.bind(this, 'GET');
f.POST = f.request.bind(this, 'POST');
