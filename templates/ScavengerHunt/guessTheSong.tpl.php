<style>
    body{
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height:100vh;
        padding:5rem;
        box-sizing: border-box;
    }
    body>div{
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        gap:1em;
    }
    .desc{
        font-size: 1.5rem;
        text-align: center;
    }
    input{
        text-align: center;
    }
</style>
</head>

<body>
    <h1>Guess The Song</h1>
    <div class = "start  <?php echo $stage>=3?"hidden":"" ?>">
        <p class = "desc">A short clip from a RV Song will be played. Guess the RV Song correctly to receive 20 points each.</p>
        <input type = "button" class = "default-button2" value = "Start">
    </div>
    <div class="song hidden">
        <audio controls>
            <source type="audio/mpeg">
        </audio>
        <p class = "desc">For Chinese Characters, you may type in Han Yu Ping Ying, or leave them in their chinese character form.</p>
        <input type = "text" class = "answer default-button2" placeholder = "Enter Song Name">
        <input type = "button" class = "submit default-button2" value = "Submit">
        <p class = "revealed hidden"></p>
    </div>
    <div class = "completed <?php echo $stage>=3?"":"hidden" ?>">
        <p class = "desc">You have completed the Guess The Song Scavenger Hunt. You have earned <span class = "points"></span> points.</p>
        <input type = "button" class = "default-button2" value = "Return to Scavenger Hunt" onclick = "window.location.href = 'index.php?filename=scavengerhunt'">
    </div>
    <script>
        var curSong = <?php echo $stage; ?>;
    </script>
    <script src = "static/js/guessTheSong.js"></script>