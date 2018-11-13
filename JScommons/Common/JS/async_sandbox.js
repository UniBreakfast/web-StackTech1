function say(what){
  var x = 0;
  while (x<200000000) x++;
  console.log(what);
}

function funcL(e) {
  //setTimeout(()=>{
    say('-A');
    say('-B');
    say('-C');
    say('-D');
    say('-E');
    say('-F');
  //},1)
}

function funcN(e) {
  e.preventDefault();
    say('-1');
    say('--2');
    say('---3');
  setTimeout(()=>{
    say('----4');
    say('-----5');
    say('------6');
  },0)
}

//funcN();
//funcL();


document.body.onclick = funcL;
document.body.oncontextmenu = funcN;

setTimeout(()=>{
  say(1);
  say(2);
  say(3);
  say(4);
  say(5);
  say(6);
},1)
say('A');
say('B');
say('C');
say('D');
say('E');
say('F');
say('G');
say('H');
say('I');
say('J');
say('K');
say('L');
setTimeout(()=>{say('===K===');},0);
say('M');
say('N');
say('O');
say('P');
say('Q');
say('R');

//document.body.onclick = (e) => console.log(e);
