<?php
include("backend/Admin/qrCode.inc.php");?>
<link href="static/css/qrCode.css" rel="stylesheet">
</head>
<div id="qr-reader"></div>
<div id="qr-reader-results"></div>
<div class = "ui">
    <div class="guest-info__container">
        <input type="text" class="name default-button">
        
        <div class="guest-list hidden">
            <div class="guest">
                <p class="guest__name">Guest 1</p>
                <p class="guest__class">Class</p>
            </div>
        </div>
    </div>
    <button class = "default-button submit-button">Submit</button>
</div>
<script>
    var guestData = <?php echo json_encode($guestInfo); ?>;
</script>
<script src="static/js/customSelect/custom-select.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="static/js/adminQr.js"></script>
