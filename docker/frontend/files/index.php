<html>
	<head>
		<title>Jeu de d�</title>	
		<script src="jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div>
			<h1>Jeu de d�</h1>
			<p>Cliquez sur le bouton pour lancer le d�.</p>
			<p><button type="button" name="dice" onClick="getJson();">Lancer le d�</button></p>

			<script>
			function getJson() {	
				$.getJSON("/back", function(result) {
					alert("hello");
					$.each(result, function(name, value) {	
						alert(value);
						$("#result").html("Vous avez tir� : ");
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