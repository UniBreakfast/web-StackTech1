'use strict';

// functions container object for common functions I use
const f = {
  // aliases
  byID:  document.getElementById.bind(document),
  newEl: document.createElement .bind(document),

  // to request do f.request(type, url, callback, reportcb, falldata, fallcb)
  request: function request(type, url, cb, reportcb, falldata, fallcb, simonly, rawphp) {
    if (simonly) falldata ? fallcb(falldata) : fallcb();
    else {
      const xhr = new XMLHttpRequest();
      xhr.open(type, url, true);
      xhr.onload = () => {
        if (fallcb) falldata ? fallcb(falldata) : fallcb();
        else if (cb) {
          if (xhr.status >= 200 && xhr.status < 400) {
            if (!xhr.response.startsWith('<?php') || rawphp) {
              if (xhr.response !== '') cb(xhr.response);
              else cb() || reportcb ? reportcb('php-response was empty') :0 ;
            }
            else if (reportcb && !rawphp) {
              reportcb('php-file content returned instead of php-response');
              falldata ? cb(falldata) : 0;
            }
          }
          else {
            if (reportcb)
              reportcb('unfortunately request.status is ' + xhr.status+
                       ' '+xhr.statusText);
            falldata ? cb(falldata) : cb();
          }
        }
      }
      xhr.onerror =
        e => reportcb(type + ' request to '+ url + ' produced ' + e);
      xhr.ontimeout =
        () => reportcb(type + ' request to '+ url + ' timed out!');
      xhr.send();
    }
  }
},

  // add style tag (with optional id) made from string to the head
  link_string_as_style_tag: (css, id) => {
    var style = f.newEl('style');
    style.textContent = css;
    if (id) style.id = id;
    document.head.appendChild(style);
  },

  link_css: (css_file) => {
    var link = f.newEl('link');
    link.setAttribute('href', css_file);
    link.setAttribute('rel', 'stylesheet');
    document.head.appendChild(link);
  },
}

// to request do f.GET(url, callback, reportcb, falldata, fallcb)
f.GET  = f.request.bind(this, 'GET');
// to request do f.POST(url, callback, reportcb, falldata, fallcb)
f.POST = f.request.bind(this, 'POST');

// add style tag (with id) made from fetched css-file to the head
f.link_css_as_style_tag =
  (css_file, id) => f.GET(css_file, css => f.link_string_as_style_tag(css, id));
