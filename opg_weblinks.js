jQuery(document).ready(function() {

	jQuery('.btnDeleteLink').click(function() {
		var url = "admin.php?page=opg_weblinks&task=remove_link&id=" + this.id;
	    var r = confirm("Está seguro de eliminar este registro?");
	    if (r == true) {
			window.location = url; 
	    }
	});
});