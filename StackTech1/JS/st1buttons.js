  // control elements container object
const c = {
  inp:       document.body.children[3], // TODO input on Enter
  btn_store: document.body.children[4],
  btn_get:   document.body.children[5]
}

c.btn_store.onclick = () => {
  const str = c.inp.value;
  if (str!=='') {
    f.POST('StackTech1/PHP/store.php?record='+str, alert, 'no PHP installed');
    c.inp.value = '';
  }
  else alert('nothing to store, input new record in the field');
}
c.btn_get.onclick   = () => f.GET( 'StackTech1/get.txt', alert, 'get2');
