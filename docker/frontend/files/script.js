function getJson()
{	
	$.getJSON("/app", function(result) {
		$("#result").hide();
		$("#result").html("Vous avez tir� : ");
		
		console.log("JSON got !");
	
		$.each(result, function(name, value) {	
			$("#result").append(value);
		});
		
		$("#result").show();
	});
}