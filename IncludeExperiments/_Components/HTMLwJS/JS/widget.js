function widget(i_num) {
  if (i>0) f.GET('_Components/HTMLwJS/HTML/tags.html', html => {
    i--;
    html = html.split('id=id').join('id=id'+i);
    f.byID('id'+i_num).innerHTML = html;
    widget(i);
  });
}
