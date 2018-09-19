  // control elements container object
const c = {
  inp:       document.body.children[3], // TODO input on Enter
  btn_store: document.body.children[4],
  btn_get:   document.body.children[5]
}

c.btn_store.onclick = () => {
  const str = c.inp.value;
  if (str!=='') {
    f.POST('PHP/store.php?record='+str, alert, 'no PHP installed');
    c.inp.value = '';
  }
  else alert('nothing to store, input new record in the field');
}
//c.btn_get.onclick = () => f.GET( 'get.txt', alert, 'get2');
//c.btn_get.onclick = () => f.GET( 'PHP/get.php', alert, 'get2');
//c.btn_get.onclick = () => f.GET( 'PHP/get.php', (echo)=>console.log(JSON.parse(echo)), 'get2');
c.btn_get.onclick = () => f.GET( 'PHP/get.php', (echo)=>console.log(JSON.parse(echo)), 'get2');
