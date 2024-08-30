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
    .teacher{
        width: 80%;
        height: 40%;
        object-fit: contain;
    
    }
</style>
</head>

<body>
    <h1>How well do you know our Teachers?</h1>
    <div class = "start <?php echo $stage>=6?"hidden":"" ?>">
        <p class = "desc">A photo of an RV teacher will be displayed. Guess the name of the teacher correctly to proceed.</p>
        <input type = "button" class = "default-button2" value = "Start">
    </div>
    <div class="song hidden">
        <img src ="" class = "teacher">
        <p class = "desc">Do not include chinese characters in your answer!</p>
        <input type = "text" class = "answer default-button2" placeholder = "Enter Teacher Name">
        <input type = "button" class = "submit default-button2" value = "Submit">
        <p class = "revealed hidden"></p>
    </div>
    <div class = "completed <?php echo $stage>=6?"":"hidden" ?>">
        <p class = "desc">You have completed the How well do you know our Teachers? Scavenger Hunt. You have earned <span class = "points"></span> points.</p>
        <input type = "button" class = "default-button2" value = "Return to Scavenger Hunt" onclick = "window.location.href = 'index.php?filename=scavengerhunt'">
    </div>
    <script>
        var curSong = <?php echo $stage; ?>;
    </script>
    <script src="//unpkg.com/string-similarity/umd/string-similarity.min.js"></script>
    <script src = "static/js/guessTheCher.js"></script>