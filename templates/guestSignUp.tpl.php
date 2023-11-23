<?php
require_once("backend/Login/guestData.inc.php");

?>
<link rel="stylesheet" href="static/css/guestSignUp.css?<?php echo time(); ?>">
</head>

<body>
    <h1>Welcome
        <?php echo $logInInfo["username"] ?>
    </h1>
    <div class="warning__container <?php echo $logInInfo["fromRVHS"] ? "hidden" : "" ?>">
        <p class="warning">It is recommended to use a <b>RVHS</b> or <b>student ICON email</b> to login. You may still
            access <b>most</b> features, but you may be prone to impersonation.</p>
        <div class="action-buttons__container">
            <button class="default-button continue">Continue?</button>
            <button class="default-button logout"
                onclick="window.location.href = 'backend/Login/logout.php'">Logout</button>
        </div>

    </div>
    <div class="main <?php echo $logInInfo["fromRVHS"] && count($similarGuests)!=0 && count($similarGuests)<5? "" : "hidden" ?>">
        <h2 class="highlight">Please confirm your name and class</h2>
        
        <?php if (count($similarGuests)<5){foreach ($similarGuests as $guest){?>
        <button class="guest <?php if (count($similarGuests)==1 ) echo "selected";?>" data-guest-id = "<?php echo $guest["guestId"]?>" >
            <p class="guest__name">
                <?php echo $guest["studentName"] ?>
            </p>
            <p class="guest__class">
                <?php echo $guest["class"] ?>
            </p>
        </button>
        <?php }}; ?>
        <div class="action-buttons__container">
            <button class="default-button error">Name Not Here</button>
            <button class="default-button submit1">Confirm</button>
        </div>

    </div>
    <div class="main2 <?php echo $logInInfo["fromRVHS"] && count($similarGuests)==0||count($similarGuests)>=5? "" : "hidden"?>">
        <h2 class="highlight">We couldn't find a guest that matches your email's username. Please provide your identity here. A moderator will verify and approve it within 24 hours.</h2>
        <label for="name">Class: </label>
        <select class="class-selector default-button">
            <?php foreach ($guestData as $class => $students): ?>
                <option value="<?php echo $class ?>">
                    <?php echo $class ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="name">Name: </label>
        <select class="name-selector default-button">
            <option value="Please Select Your Class">Please Select Your Name</option>
        </select>
        <button class="default-button submit2">Confirm</button>
    </div>
    </div>
    <script>
        const guestData = <?php echo json_encode($guestData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
        const username = <?php echo json_encode($logInInfo["username"], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
        var guestId = <?php echo count($similarGuests) == 1?$similarGuests[0]["guestId"]:-1;?>;
    </script>
    <script src="static/js/guestSignUp.js"></script>