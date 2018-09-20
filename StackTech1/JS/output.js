function output(element, headerows){
  var html = '';
  headerows.rows.forEach(item =>
    html += '<li><div class=li>'+
      '<span class=remove>&times     </span>&nbsp'+
      '<span class=record>'+item[0]+'</span>&nbsp'+
      '<span class=date>'  +item[1]+'</span>'+
    '</div></li>');
  element.outerHTML = '<ol>'+html+'</ol>';
}
