<?php require "backend/Votings/sectionData.inc.php" ?>
<link rel="stylesheet" href="static/css/voting.css?<?php echo time(); ?>">
<link rel="stylesheet" href="static/css/nomination.css?<?php echo time(); ?>">
<meta name="pageTitle" content="voting">

</head>

<body style="height: 100%;">
    <section class="hero">
        <img src="static/assets/about/logo.png">
        <h1>Votings</h1>
        <h2>2023 JC 2 Prom</h2>
        <button class="default-button">Sign In</button>
    </section>
    <div class="nomination hidden">
        <h2>Nomination For <span class="nomination-type">Partners In Crime</span></h2>
        <select class="class default-button">
            <option value="">Please Select a Class</option>
        </select>
        <select class="name default-button">
            <option value="">Please Select a Student</option>
        </select>
        <input type = "file" class = "image default-button" accept = "image/*">
        <textarea class="reason default-button" placeholder="Reason for Nomination"></textarea>
        <button class="nominate default-button">Nominate</button>
    </div>
    <!-- <section class = "best-dressed-male">
        <div class = "header">
            <h2>Best Dressed Male</h2>
            <button class = "default-button">Nominate</button>
        </div>
        <div class = "nominee__container">
            <div class = "nominee">
                <img class = "nominee__img"> 
                <p class = "nominee__name">Name</p>
                <p class = "nominee__table">Table <span class = "table-no"></span></p>
            </div>
        </div>
    </section>  

    <section class = "best-dressed-female">
        <div class = "header">
            <h2>Best Dressed Female</h2>
            <button class = "default-button">Nominate</button>
        </div>
        <div class = "nominee__container">
            <div class = "nominee">
                <img class = "nominee__img"> 
                <p class = "nominee__name">Name</p>
                <p class = "nominee__table">Table <span class = "table-no"></span></p>
            </div>
        </div>
    </section>  -->

    <section class="partners-in-crime" id = "pic">
        <div class="header">
            <h2>Partners in Crime</h2>
            <button class="default-button nominate-button" id="nominate-pic">Nominate</button>
        </div>
        <input type="text" class="search default-button" placeholder="Search by Name or Class">
        <div class="nominee__container">
            <?php foreach ($guestData[0] as $guestId => $guest) { ?>
                <div class="nominee <?php if ($guest["voted"]) echo "voted";?>" data-guest-id = "<?php echo htmlspecialchars($guestId);?>">
                    <div class = "nominee-img__container">
                        <img class="nominee__img" src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>">
                        <p class = "nominee__desc"><?php echo htmlspecialchars($guest["description"]);?></p>
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

    <section class="prom-king">
        <div class="header">
            <h2>Prom King</h2>
            <button class="default-button nominate-button">Nominate</button>
        </div>
        <input type="text" class="search default-button" placeholder="Search by Name or Class">

        <div class="nominee__container">
            <?php foreach ($guestData[1] as $guest) { ?>
                <div class="nominee">
                    <img class="nominee__img" src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>">
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

    <section class="prom-queen">
        <div class="header">
            <h2>Prom Queen</h2>
            <button class="default-button nominate-button">Nominate</button>
        </div>
        <input type="text" class="search default-button" placeholder="Search by Name or Class">

        <div class="nominee__container">
            <div class="nominee">
                <img class="nominee__img">
                <p class="nominee__name">Name</p>
                <p class="nominee__table">Table <span class="table-no"></span></p>
            </div>
        </div>
    </section>
    <script>
        const nomineesData = <?php echo json_encode($guestData); ?>;
    </script>
    <script src="static/js/nominate.js"></script>
    <script src="static/js/voting.js"></script>
    <script src="static/js/search.js"></script>