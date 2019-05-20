jQuery(document).ready(function () {
	jQuery.ajax({
        type: 'GET',
        async: false,
        url: "./index.php",
        dataType: 'json',
        data: { action : "btnglobal"}
    }).done(function(response) {
		jQuery("#headerText").after(response);
	});
})
function disconnect() {
	jQuery.ajax({
		type: 'GET',
		async: false,
		url: "./index.php",
		dataType: 'json',
		data: { action : "deconnexion"}
	}).done(function(response) {
		console.log(response);
		if (response == true) {
			
			window.location.href = "./index.php?action=vueConnexion";
		}
	});
}