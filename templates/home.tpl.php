<link rel="stylesheet" href="static/css/home.css?<?php echo time(); ?>">
<meta name="pageTitle" content="home">
</head>

<body style="height: 100%;">
    <?php require "templates/defaults/nav.tpl.php" ?>
    <section class="hero">
        <img src="static/assets/default/logo.png">
        <h1>Carpe Noctem</h1>
        <h2>2023 JC 2 Prom</h2>
        <?php require_once "templates/defaults/signin.tpl.php"?>
    </section>
    <div class="general-details">

        <!-- <section class="programme-flow">
            <h2>Programme Flow</h2>
            <table class =  "fadeTop paused">
            <tr>
                <th>Time</th>
                <th>Programme</th>
            </tr>    
        </table>
        </section> -->
        <section class="details">
            <h2>Details</h2>
            <div class="details__datetime fade-left paused">
                <img src="static/assets/about/date.png">
                <p class="date">Monday, 4th December</p>
                <p class="time">7pm - 10.30pm</p>
            </div>
            <div class="details__location fade-right paused">
                <img class="mt_hotel" src="static/assets/about/mariottTangHotel.png">
                <p class="venue">Marriott Tang Hotel, Level 3</p>
                <a href="https://www.google.com/maps/place/Singapore+Marriott+Tang+Plaza+Hotel/@1.2823857,103.8080338,12z/data=!3m1!5s0x31da198d87d8f16f:0xfeeaa464762d4970!4m14!1m7!3m6!1s0x31da198d87a237ef:0x37afa654c06dddb2!2sSingapore+Marriott+Tang+Plaza+Hotel!8m2!3d1.3052151!4d103.8329675!16s%2Fg%2F122_9hth!3m5!1s0x31da198d87a237ef:0x37afa654c06dddb2!8m2!3d1.3052151!4d103.8329675!16s%2Fg%2F122_9hth?hl=en-US&entry=ttu"
                    target="_blank">
                    <button class="default-button">View On Google Maps</button>
                </a>
            </div>
        </section>
        <section class="dress-code">
            <h2>Dress Code</h2>
            <!-- <p class="dress-code-description fade-left paused">The dress code is <b>Smart Casual</b> <br><br> Stick to
                our colour theme! Wear <b>blue</b> and/or <b>black</b> outfits</p>
            <img class="dress-code-example fade-right paused" src="static/assets/about/dressCode.png"> -->
            <p class="masquerade-description fade-right paused">It’s a Masquerade Ball! You can choose to <b>Buy</b> or <b>Create</b> your
                own unique masks before the event!</p>
            <img class="masquerade-example fade-left paused" src="static/assets/about/masquerade.png">
            <iframe class="mask-making-vid fade-bottom paused"
                src="https://drive.google.com/file/d/1TowF6-xmex1-Dw4bbNw9rnKA9znTsm--/preview" allow="autoplay">

            </iframe>
        </section>
        <div class="bg"></div>
    </div>
    <div class="programmes">

        <section class="storyline">
            <h2>Storyline</h2>
            <!-- <video>
                <source src="static/assets/about">
            </video> -->
            <!-- yes its inline styling, but its temporary so who cares -->
            <p class="fade-bottom paused storyline-details">The antagonist we first
                encountered, <b>Eris</b>, has returned, driven by a quest for revenge! It's time for us to unite and
                assist our hero, <b>Parker Peter</b>, in triumphing over Eris once more. Let's band together for this
                epic showdown!</p>
        </section>

        <section class="scavenger-hunt">
            <h2>Scavenger Hunt</h2>
            <p class="desc1 fade-left paused">Our Mighty Hero <b>Parker Peter’s</b> identity is at risk due to some
                inadvertently left clues!
            </p>
            <img class="img1 fade-right paused" src="static/assets/about/scavengerHunt.png">
            <div class="desc2 fade-right paused">
                <p>Scan the <b>15 QR codes hidden</b> around the venue and perform a task to eliminate these clues
                    before they
                    fall into Eris's hands. Let's protect our hero's secret together!</p>
                <!-- <button class="default-button">Scan</button> -->
            </div>
            <img class="img2 fade-left paused" src="static/assets/about/venue.png">
        </section>

        <section class="booth-games">
            <h2>Booth Games</h2>
            <img class="img3 fade-left paused" src="static/assets/about/boothGamePrizes.png">
            <p class="desc3 fade-right paused">Join the mission to equip Peter Parker with vital <b>medical
                    supplies!</b> Test your skills at our
                themed booth games and stand a chance to win
                amazing prizes.</p>
        </section>
        <div class="bg"></div>
    </div>

    <script src="static/js/home.js?<?php echo time(); ?>"></script>