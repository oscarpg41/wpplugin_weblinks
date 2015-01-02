function borrarLink(id){
	var url = "admin.php?page=opg_weblinks&task=remove_link&id=" + id;
    var r = confirm("Está seguro de eliminar este registro?");
    if (r == true) {
		window.location = url; 
    }
}