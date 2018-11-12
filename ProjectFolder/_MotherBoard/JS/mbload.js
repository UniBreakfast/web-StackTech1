mb_more.styles = document.createElement('link');
mb_more.styles.setAttribute('href', '../_MotherBoard/CSS/mb.css');
mb_more.styles.setAttribute('rel', 'stylesheet');
document.head.appendChild(mb_more.styles);

mb_more.styles = document.createElement('link');
if (mb_more.alt_mbvars_css)
     mb_more.styles.setAttribute('href', mb_more.alt_mbvars_css);
else mb_more.styles.setAttribute('href', '../_MotherBoard/CSS/mbvars.css');
mb_more.styles.setAttribute('rel', 'stylesheet');
document.head.appendChild(mb_more.styles);

/* // alternative way to load motherboard css
f.GET('../_MotherBoard/CSS/mb.css', mb_css =>
  f.GET('../_MotherBoard/CSS/mbvars.css', mbvars_css =>
    f.link_string_as_style_tag(mb_css + mbvars_css, 'mb-style'))); */


f.GET('../_MotherBoard/HTML/mb.htm', htm => {
  if (mb_more && mb_more.css) f.link_string_as_style_tag(mb_more.css);

  document.body.innerHTML = htm;

  if (mb_more && mb_more.data)
    for (let key in mb_more.data) f.byID(key).innerHTML = mb_more.data[key];

  delete window.mb_more;
});

