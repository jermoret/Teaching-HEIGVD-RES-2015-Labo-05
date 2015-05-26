<html>
	<head>
		<title>Jeu de d�</title>	
		<script src="jquery-2.1.4.min.js"></script>
		<script>
			function getJson() {	
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
		</script>
	</head>
	<body>
		<div>
			<h1>Jeu de d�</h1>
			<p>Cliquez sur le bouton pour lancer le d�.</p>
			<p><button type="button" name="dice" onClick="getJson();">Lancer les d�s</button></p>

			<div id="result" class="result"></div>
		</div>
	</body>
</html>