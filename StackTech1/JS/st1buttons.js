  // control elements container object
const c = {
  btn_store: document.body.children[3],
  btn_get: document.body.children[4]
}

c.btn_store.onclick = () => f.post('store');
c.btn_get.onclick   = () => f.get('get');
