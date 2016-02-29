<HTML>

<head>
	<title>Real Time Updater - "Ticker"</title>
	<!--Note: All styles should be compacted into one, single CSS file-->
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<link rel="stylesheet" type="text/css" href="../globalAssets/global.css" />
	<script src="../globalAssets/jquery-1.12.1.min.js"></script>
</head>

<body>
	<span id="numberOfPeople">This text will update</span>
	&nbsp;&nbsp;
	<span class="flasher">Updated.</span>
	
	
	<script>
		//Populates a given span element with data from server, "server.php".
		//Uses a GET request.
		function updateMe(element) {
			$.get( "server.php", function( data ) {
			  $( element ).html( data );
			});
		}
		
		//Flashes a given element using the css opacity attribute.
		function flashMe(element) {
			$(element).css("opacity", 1);
			$(element).animate({
				opacity:0
			}, "slow");
		}
		
		//This section simply acts as "main."
		$( document ).ready(function() {
			updateMe("#numberOfPeople");
			
			//Runs every five seconds.
			window.setInterval(function(){
				updateMe("#numberOfPeople");
				flashMe(".flasher");
			}, 5000);
		});
	</script>
</body>

</HTML>

