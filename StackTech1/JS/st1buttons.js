  // control elements container object
const c = {
  btn_store: document.body.children[3],
  btn_get:   document.body.children[4]
}

c.btn_store.onclick = () => f.POST('StackTech1/PHP/store.php', alert, 'store2');
c.btn_get.onclick   = () => f.GET( 'StackTech1/get.txt', alert, 'get2');
