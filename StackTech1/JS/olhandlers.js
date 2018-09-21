c.ol.onclick = e => {
  if (e.target.className == 'remove') {
    f.POST('PHP/remove.php?recid='+e.target.dataset.id);
    c.btn_get.click();
  }
}
