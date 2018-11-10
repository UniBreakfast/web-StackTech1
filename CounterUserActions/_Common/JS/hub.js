'use strict';

// pubsub hub - is a Hub module to use in Publish/Subscribe pattern
const hub = (() => {

  // private storage for existing subscriptions with callbacks for event handling
  const subs = {}

  // publish a named event for somebody subscribed to act upon given data
  function pub(e_name, data) {
    if (subs[e_name] && subs[e_name].length)
      subs[e_name].map(item=>item).forEach(cb => cb(data));
    return this;
  }

  // subscribe to be called with a callback upon a certain event
  function sub(e_name, cb) {
    if (!subs[e_name]) subs[e_name] = [cb];
    else subs[e_name].push(cb);
    return this;
  }

  // unsubscribe certain callback from a certain event or whole event
  function unsub(e_name, cb) {
    if (!cb && subs[e_name]) delete subs[e_name];
    else {
      if (subs[e_name]) {
        var item = subs[e_name];
        item.splice(item.indexOf(cb), 1);
      }
      if (!item.length) delete subs[e_name];
    }
    return this;
  }

  // subscribe to be called only once with a callback upon a certain event
  function sub1(e_name, cb) {
    function temporary(data) {
      cb(data);
      unsub(e_name, temporary);
    }
    sub(e_name, temporary);
    return this;
  }

  // list registered event subscriptions or callback handlers for events
  const list = (e_name) => {
    if (e_name) return subs[e_name] || 'none';
    else return Object.keys(subs);
  }

  // public methods for hub object: publish (triggers event handlers), subscribe, unsubscribe, list
  const hub = {list};
  hub.pub   =   pub.bind(hub);
  hub.sub   =   sub.bind(hub);
  hub.unsub = unsub.bind(hub);
  hub.sub1  =  sub1.bind(hub);

  // private storage for group subscriptions with callbacks for event handling
  const gr_subs = {}

  // subscribe a callback for a full group of event publishes
  function gr_sub(e_name, cb) {
    if (!gr_subs[e_name]) gr_subs[e_name] = {cb: [cb]};
    else if (gr_subs[e_name].cb) gr_subs[e_name].cb.push(cb);
    else gr_subs[e_name].cb = [cb];
    return this;
  }

  // publish one event of a group
  function gr_pub(e_name, num, data_name, data) {
    if (!gr_subs[e_name]) gr_subs[e_name] = {num: num, got:1}
    else {
      gr_subs[e_name].num = num;
      if  (gr_subs[e_name].got) gr_subs[e_name].got++;
      else gr_subs[e_name].got = 1;
    }
    if (data_name) {
      if (!gr_subs[e_name].data) gr_subs[e_name].data = {}
      gr_subs[e_name].data[data_name] = data;
    }
    if (num == gr_subs[e_name].got && gr_subs[e_name].cb.length) {
      gr_subs[e_name].cb.forEach(cb => cb(gr_subs[e_name].data));
      delete gr_subs[e_name].got;
      delete gr_subs[e_name].data;
    }
    return this;
  }

  // subscribe to and publish to a group event at once
  function gr_subpub(e_name, cb, num, data_name, data) {
    gr_sub(e_name, cb);
    gr_pub(e_name, num, data_name, data);
    return this;
  }

  // unsubscribe whole group event or certain callback from it
  function gr_unsub(e_name, cb) {
    if (!cb && gr_subs[e_name]) delete gr_subs[e_name];
    else {
      if (gr_subs[e_name]) {
        var item = gr_subs[e_name].cb;
        item.splice(item.indexOf(cb), 1);
      }
      if (!item.length) delete gr_subs[e_name];
    }
    return this;
  }

  hub.gr_pub    =    gr_pub.bind(hub);
  hub.gr_sub    =    gr_sub.bind(hub);
  hub.gr_unsub  =  gr_unsub.bind(hub);
  hub.gr_subpub = gr_subpub.bind(hub);

  return hub;
})();

