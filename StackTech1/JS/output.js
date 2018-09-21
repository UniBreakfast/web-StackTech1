function output(element, headerows){
  var html = '';
  headerows.rows.forEach(item =>
    html += '<li><div class=li>'+
      '<span class=remove data-id='+item[0]+'>&times</span>&nbsp'+
      '<span class=record>'        +item[1]+       '</span>&nbsp'+
      '<span class=date>'          +item[2]+       '</span>'+
    '</div></li>');
  element.innerHTML = html;
}
