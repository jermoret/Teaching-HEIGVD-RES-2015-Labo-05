<html>
	<head>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<title>RES - Labo 05</title>
	</head>
	
	<body>
		<h2>You dice 
			<script>
				$.getJSON( "test.js", function( json ) {
					console.log( "JSON Data: " + json.users[ 3 ].name );
				});
			</script>
		</h2>
	</body>
</html>