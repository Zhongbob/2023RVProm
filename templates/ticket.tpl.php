<link rel="stylesheet" href="static/css/ticket.css?<?php echo time(); ?>">
</head>

<body>
    <div class="main">
        <img src="static/assets/ticket/ticket.png" class="ticket">
        <div class="guest-code__container">
            <div id="guest-code"></div>
            <div class="guest-code__name"><?php echo $guestInfo["studentName"]?></div>
            <div class="guest-code__table">Table <?php echo $guestInfo["tableNo"]?></div>
        </div>
    </div>
    <script src="static/qrcode/qrcode.min.js"></script>
    <script src="static/js/ticket.js" defer></script>