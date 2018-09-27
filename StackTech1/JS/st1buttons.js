  // control elements container object
const c = {
  inp:       document.body.children[3],
  btn_store: document.body.children[4],
  btn_get:   document.body.children[5],
  btn_clear: document.body.children[6],
  ol:        document.body.children[7]
}

c.inp.onkeydown = e => {
  if (e.keyCode == 13) c.btn_store.click();
}

c.btn_store.onclick = () => {
  const str = c.inp.value;
  if (str!=='') {
    f.POST('PHP/store.php?record='+str, ()=>c.btn_get.click(),
           'no PHP response', alert);
    c.inp.value = '';
  }
  else alert('nothing to store, input new record in the field');
}

//c.btn_get.onclick = () => f.GET('get.txt', alert, 'got as fallback');
//c.btn_get.onclick = () => f.GET('PHP/get.php', alert, 'get2');
//c.btn_get.onclick = () => f.GET('PHP/get.php', (echo)=>console.log(JSON.parse(echo)), 'get2');
c.btn_get.onclick =
  () => f.GET('PHP/get.php', (echo)=>{
    output(c.ol, JSON.parse(echo));
}, '{"rows":[[1, "Not","2018-09-17 21:03:03"],                                           \
             [2, "a","2018-09-17 21:04:11"],                                             \
             [3, "real data","2018-09-17 21:41:32"],                                     \
             [4, "taken","2018-09-17 22:03:10"],                                         \
             [5, "from","2018-09-17 22:13:18"],                                          \
             [6, "MySQL database","2018-09-18 07:34:03"],                                \
             [7, "but","2018-09-18 10:47:03"],[8, "placeholder","2018-09-19 20:05:18"],  \
             [9, "Because PHP is not working here","2018-09-20 09:35:48"]]}');
//  }, 'no PHP response', alert);

c.btn_clear.onclick = () => { if (confirm('Are you sure you want to DELETE ALL RECORDS?')) {
  f.GET('PHP/clear.php', ()=>{});
  c.ol.innerHTML = '';
} };
