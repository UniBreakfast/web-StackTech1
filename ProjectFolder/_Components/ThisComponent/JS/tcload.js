
c.tc = (container, title, paragraph, replace) => {
  f.link_css('../_Components/ThisComponent/CSS/thiscomponent.css');

  f.GET('../_Components/ThisComponent/HTML/thiscomponent.htm', htm => {
    if (title)     htm = htm.replace('Title', title);
    if (paragraph) htm = htm.replace('A paragraph of text.', paragraph);
    if (replace) container.innerHTML  = htm;
    else         container.innerHTML += htm;
  });
}

c.tc_ = (container, title, paragraph) =>
  c.tc(container, title, paragraph, true);
