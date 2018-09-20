  // control elements container object
const c = {
  inp:       document.body.children[3], // TODO input on Enter
  btn_store: document.body.children[4],
  btn_get:   document.body.children[5],
  ol:        document.body.children[6]
}

c.btn_store.onclick = () => {
  const str = c.inp.value;
  if (str!=='') {
    f.POST('PHP/store.php?record='+str, alert, 'no PHP response');
    c.inp.value = '';
  }
  else alert('nothing to store, input new record in the field');
}
//c.btn_get.onclick = () => f.GET('get.txt', alert, 'get2');
//c.btn_get.onclick = () => f.GET('PHP/get.php', alert, 'get2');
//c.btn_get.onclick = () => f.GET('PHP/get.php', (echo)=>console.log(JSON.parse(echo)), 'get2');
c.btn_get.onclick =
  () => f.GET('PHP/get.php', (echo)=>{
    output(c.ol, JSON.parse(echo));
    c.ol = document.body.children[6];
//}, '{"rows": [["none", "2018-09-17 21:03:03"]]}');
}, '{"headers":["record","dt_create"],"rows":[["qwerty","2018-09-17 21:03:03"],["asdf","2018-09-17 21:04:11"],["zxcvb","2018-09-17 21:16:55"],["uiop","2018-09-17 21:41:32"],["gh","2018-09-17 22:03:10"],["pharaoh","2018-09-17 22:13:18"],["adfa;jkag;kl","2018-09-18 07:34:03"],["check","2018-09-18 10:47:03"],["asdfafsdfsadfsafasdf","2018-09-19 20:05:18"],["Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus beatae, molestias cum! Animi molestias earum exercitationem, ex illo unde! Dolor voluptatibus accusantium voluptatem eos quia, voluptate, non necessitatibus quod saepe perspiciatis rati","2018-09-20 09:35:48"]]}');
//  }, 'no PHP response', alert);
