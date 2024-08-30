<style>
    body{
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height:100vh;
        padding:5rem;
        box-sizing: border-box;
        text-align: center;
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
    .place{
        width: 80%;
        height: 40%;
        object-fit: contain;
        filter:grayscale(100%);
        transition: filter 0.5s;
    }
    .show-color{
        filter:grayscale(0%);
    }
</style>
</head>

<body>
    <h1>How Well do you know the school?</h1>
    <div class = "start <?php echo $stage>=3?"hidden":"" ?>">
        <p class = "desc">A photo of a room in RV will be displayed. Guess the name of the room to proceed.</p>
        <input type = "button" class = "default-button2" value = "Start">
    </div>
    <div class="song hidden">
        <img src ="" class = "place">
        <input type = "text" class = "answer default-button2" placeholder = "Enter Location Name">
        <input type = "button" class = "submit default-button2" value = "Submit">
        <p class = "revealed hidden"></p>
    </div>
    <div class = "completed <?php echo $stage>=3?"":"hidden" ?>">
        <p class = "desc">You have completed the How Well Do You Know The School? Scavenger Hunt. You have earned <span class = "points"></span> points.</p>
        <input type = "button" class = "default-button2" value = "Return to Scavenger Hunt" onclick = "window.location.href = 'index.php?filename=scavengerhunt'">
    </div>
    <script>
        var curSong = <?php echo $stage; ?>;
    </script>
    <script src="//unpkg.com/string-similarity/umd/string-similarity.min.js"></script>
    <script src = "static/js/guessTheLocation.js"></script>