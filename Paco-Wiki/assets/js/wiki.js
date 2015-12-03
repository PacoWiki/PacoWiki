function wiki_toggle_content_table(event) {
	var link = document.getElementById("wiki-toggle-table-of-contents-link");
	
	var link_text = link.innerHTML;
	link.innerHTML = link.getAttribute("showhide"); 
	link.setAttribute("showhide" , link_text);
	
	var e = document.getElementById('wiki-table-of-contents-list');
	if(e.style.display == 'none')
		e.style.display = 'block';
	else
		e.style.display = 'none';
	return event.preventDefault();
}