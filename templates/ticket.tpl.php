<link rel="stylesheet" href="static/css/ticket.css?<?php echo time(); ?>">
<meta name="pageTitle" content="ticket">

</head>

<body>
<?php require "templates/defaults/nav.tpl.php"?>

    <?php if (!$guestInfo){
        echo "<script>alert('Not Logged in! Please login before continuing');window.location.href = 'index.php'</script>";
        }?>
    <?php if (!$logInInfo["fromRVHS"]){?>
        <p class = "reject">Sorry! Tickets are only available if you are a confirmed RVian. 
            Please login with <b>Student ICON email, or RVHS email</b>. If you are unable to login, you can contact <b>rdevcca@gmail</b> with proof of identity, or bring your <b>NRIC</b> to the prom venue on the actual day.</p>
    <?php } else{?>

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
    <?php } ?>