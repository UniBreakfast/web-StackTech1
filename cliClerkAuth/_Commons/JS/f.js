'use strict';

// functions container object for common functions I use
const f = {
  // aliases
  byID:  document.getElementById.bind(document),
  newEl: document.createElement .bind(document),

  // to request do f.request(type, url, callback, reportcb, falldata, fallcb)
  request: function request(type, url, callback, reportcb, falldata, fallcb) {
    const request = new XMLHttpRequest();
    request.open(type, url, true);
    request.onload = () => {
      if (callback) {
        if (request.status >= 200 && request.status < 400) {
          if (!request.response.startsWith('<?php')) {
            if (request.response !== '') callback(request.response);
            else callback();
          }
          else if (reportcb)
            reportcb('php file content returned instead of php-response');
        }
        else {
          if (reportcb) reportcb('request.status is ' + request.status);
          falldata ? callback(falldata) : callback();
        }
      }
      else if (fallcb) falldata ? fallcb(falldata) : fallcb();
    }
    request.onerror =
      e => reportcb(type + ' request to '+ url + ' produced ' + e);
    request.ontimeout =
      () => reportcb(type + ' request to '+ url + ' timed out!');
    request.send();
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

//let log = console.log;

function log(subj) {
  if (subj.msg) console.log(subj.msg.type+' '+subj.msg.code+': '+subj.msg.text);
  else console.log(subj);
}
