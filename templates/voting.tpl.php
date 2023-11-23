<?php require "backend/Votings/sectionData.inc.php" ?>
<link rel="stylesheet" href="static/css/voting.css?<?php echo time(); ?>">
<link rel="stylesheet" href="static/css/nomination.css?<?php echo time(); ?>">
<link rel="stylesheet" href="static/js/customSelect/custom-select.css">
<meta name="pageTitle" content="voting">


</head>

<body style="height: 100%;">
    <?php require "templates/defaults/nav.tpl.php" ?>

    <section class="hero">
        <img src="static/assets/default/logo.png">
        <h1>Votings</h1>
        <h2>2023 JC 2 Prom</h2>
        <?php require_once "templates/defaults/signin.tpl.php" ?>
    </section>
    <div class="nomination hidden">
        <?php
        if (!$logInInfo || !$logInInfo["fromRVHS"]) {
            ?>
            <h2>You must be a verified user to Nominate a Peer</h2>
            <button class="cancel default-button">Cancel</button>
            <?php
        } ?>
        <div id = "max" class="tmp hidden">
            <h2>Nomination For <span class="nomination-type">Partners In Crime</span></h2>
            <h3 style = "font-size:2em;">You have already nominated 3 people for this category</h3>
            <button class="cancel default-button">Cancel</button>
        </div>
        <div id = "main" class="tmp">
                <h2>Nomination For <span class="nomination-type">Partners In Crime</span></h2>
                <h3 class="remaining"><span>3</span> Nominations Left for this category</h3>
                <div class="guest-info__container">
                    <input type="text" class="name default-button">
                    <select class="class default-button">
                        <option value="">CLASS</option>
                    </select>
                    <div class="guest-list hidden">
                        <div class="guest">
                            <p class="guest__name">Guest 1</p>
                            <p class="guest__class">Class</p>
                        </div>
                    </div>
                </div>
                <div class="guest-details__container">
                    <div class="image">
                        <input type="file" id="image-upload" accept="image/*">
                        <label class="image-upload__text" for="image-upload">Upload Image of Student! <br> Recommended
                            Aspect Ratio is 2:3</label>
                        <img class="image-preview">

                    </div>
                    <textarea class="reason default-button" placeholder="Reason for Nomination"></textarea>
                </div>
                <div class="action-button__container">
                    <button class="cancel default-button">Cancel</button>
                    <button class="nominate default-button">Nominate</button>
                </div>
            </div>
        </div>
        <section class="partners-in-crime" id="pic">
            <div class="header">
                <h2>Partners in Crime</h2>
                <button class="default-button nominate-button" id="nominate-pic">Nominate</button>
            </div>
            <div class="misc">
                <input type="text" class="search default-button" placeholder="Search by Name or Class">
                <p class="remaining-votes"><span>
                    <?php echo $remainingVotes[0] ?>
                </span>&nbsp;Votes Left</p>
        </div>
        <div class="nominee__container">
            <?php foreach ($guestData[0] as $guestId => $guest) { ?>
                <div class="nominee <?php if ($guest["voted"])
                    echo "voted"; ?>" data-guest-id="<?php echo htmlspecialchars($guestId); ?>">
                    <div class="nominee-img__container">
                        <img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>">
                        <p class="nominee__desc">
                            <?php echo htmlspecialchars($guest["description"]); ?>
                        </p>
                        <div class="heart"></div>

                    </div>
                    <p class="nominee__name">
                        <?php echo htmlspecialchars($guest["studentName"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="nominee__table">
                        <?php echo htmlspecialchars($guest["class"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="prom-king" id="pk">
        <div class="header">
            <h2>Prom King</h2>
            <button class="default-button nominate-button">Nominate</button>
        </div>
        <div class="misc">
            <input type="text" class="search default-button" placeholder="Search by Name or Class">
            <p class="remaining-votes"><span>
                    <?php echo $remainingVotes[1] ?>
                </span>&nbsp;Votes Left</p>
        </div>

        <div class="nominee__container">
            <?php foreach ($guestData[1] as $guest) { ?>
                <div class="nominee <?php if ($guest["voted"])
                    echo "voted"; ?>" data-guest-id="<?php echo htmlspecialchars($guestId); ?>">
                    <div class="nominee-img__container">
                        <img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>">
                        <p class="nominee__desc">
                            <?php echo htmlspecialchars($guest["description"]); ?>
                        </p>
                        <div class="heart"></div>

                    </div>
                    <p class="nominee__name">
                        <?php echo htmlspecialchars($guest["studentName"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="nominee__table">
                        <?php echo htmlspecialchars($guest["class"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="prom-queen" id="pq">
        <div class="header">
            <h2>Prom Queen</h2>
            <button class="default-button nominate-button">Nominate</button>
        </div>
        <div class="misc">
            <input type="text" class="search default-button" placeholder="Search by Name or Class">
            <p class="remaining-votes"><span>
                    <?php echo $remainingVotes[2] ?>
                </span>&nbsp;Votes Left</p>
        </div>

        <div class="nominee__container">
            <?php foreach ($guestData[2] as $guest) { ?>
                <div class="nominee <?php if ($guest["voted"])
                    echo "voted"; ?>" data-guest-id="<?php echo htmlspecialchars($guestId); ?>">
                    <div class="nominee-img__container">
                        <img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>">
                        <p class="nominee__desc">
                            <?php echo htmlspecialchars($guest["description"]); ?>
                        </p>
                        <div class="heart"></div>

                    </div>
                    <p class="nominee__name">
                        <?php echo htmlspecialchars($guest["studentName"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="nominee__table">
                        <?php echo htmlspecialchars($guest["class"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </section>
    <script>
        const nomineesData = <?php echo json_encode($guestData); ?>;
        const remainingVotes = <?php echo json_encode($remainingVotes); ?>;
        const loggedIn = <?php echo $logInInfo ? "true" : "false"; ?>;
    </script>
    <script src="static/js/customSelect/custom-select.min.js" type="text/javascript"></script>
    <script src="static/js/nominate.js"></script>
    <script src="static/js/imageUpload.js"></script>
    <script src="static/js/voting.js"></script>
    <script src="static/js/search.js"></script>