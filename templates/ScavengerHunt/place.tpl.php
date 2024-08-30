<?php $versionNo = time()?>
	<link rel="stylesheet" type="text/css" href="static/css/place/colorPalette.css?<?php echo $versionNo; ?>">
	<link rel="stylesheet" type="text/css" href="static/css/place/canvas.css?<?php echo $versionNo; ?>">
	<link rel="stylesheet" type="text/css" href="static/css/place/loading.css?<?php echo $versionNo; ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P">
</head>

<body>
	<?php require_once "templates/ScavengerHunt/placeLoading.tpl.php"; ?>
		<div id="colorpick">
			<div id="colorrange">

				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<div class="colors">

				</div>
				<input type="color" id="colorpicker">
				<input type="button" id="canvas-picker" value="Pick From Canvas">
			</div>
			<div class="options">
				<input type="image" class="zoom zoom-in" src="static/assets/scavengerHunt/zoom-in.png">
				<div class = "default-button2" id="submitcolor">
					<span id="main-text" style="position:relative;bottom:0.15em;">COLOR PIXEL (<span id = "pixels-left"></span>)</span>
					<span id="time-left" style="position:relative;bottom:0.15em;"></span>
					<img src="static/assets/scavengerHunt/loading.gif" id="loading">
				</div>
				<input type="image" class="zoom zoom-out" src="static/assets/scavengerHunt/zoom-out.png">
			</div>



		</div>

	<div id="canvas">
	</div>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/pixi.js@7.x/dist/pixi.min.js"></script>
	<script src="static/js/place/canvas.js?<?php echo $versionNo; ?>" defer></script>
	<script src="static/js/place/place.js?<?php echo $versionNo; ?>"defer ></script>
	<script src="static/js/place/loading.js" defer>
	</script>
