f.link_css('../_MotherBoard/CSS/mb.css');

if (mb_more.alt_mbvars_css) f.link_css(mb_more.alt_mbvars_css);
else                        f.link_css('../_MotherBoard/CSS/mbvars.css');

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

  hub.gr_go('template_done');
});

