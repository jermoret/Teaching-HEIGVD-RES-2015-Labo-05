<html>
	<head>
		<title>Jeu de dé</title>	
		<script src="jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div>
			<h1>Jeu de dé</h1>
			<p>Cliquez sur le bouton pour lancer le dé.</p>
			<p><button type="button" name="dice" onClick="getJson();">Lancer le dé</button></p>

			<script>
			function getJson() {	
				$.getJSON("/back", function(result) {
					alert("hello");
					$.each(result, function(name, value) {	
						alert(value);
						$("#result").html("Vous avez tiré : ");
						$("#result").append(value);
					});
					
					$("#result").show();
				});
			}
			</script>
			<div id="result" class="result"></div>
		</div>
	</body>
</html>