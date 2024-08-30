<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        padding: 5rem;
        box-sizing: border-box;
        text-align: center;
    }

    body>div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        gap: 1em;
    }

    .desc {
        font-size: 1.5rem;
        text-align: center;
    }
    .message{
        font-size:2rem;
        font-weight: bold;

    }
    input {
        text-align: center;
    }

    .teacher {
        width: 80%;
        height: 40%;
        object-fit: contain;

    }

    table {
        width: 80%;
        border-collapse: collapse;
        font-size: 1.5rem;
    }

    th,
    td {
        border: 1px solid white;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: var(--secondary);
    }
</style>
</head>

<body>
    <h1>What's the Password?</h1>
    <?php if ($logInInfo["userid"]%2==0){?> 
    <div class="message main <?php echo $stage>=1?"hidden":"" ?>">
        <p class="desc">Below is a encrypted secret message. Find a friend who has the cipher to decrypt the message.
        </p>
        <p class="message">& < - ! * & ] * %</p>
        <input type="text" class="answer default-button2" placeholder="Enter Message">
        <input type="button" class="submit default-button2" value="Submit">
        <p class="revealed hidden"></p>
    </div>
    <?php } else {?> 
    <div class="cipher main">
        <p class="desc">Below is a cipher. Find a friend who has the secret message and use the cipher to decrypt the
            message.</p>
        <table>
            <tr>
                <th>Symbol</th>
                <th>Letter</th>
            </tr>
            <tr>
                <td>#</td>
                <td>A</td>
            </tr>
            <tr>
                <td>[</td>
                <td>B</td>
            </tr>
            <tr>
                <td>]</td>
                <td>C</td>
            </tr>
            <tr>
                <td>=</td>
                <td>D</td>
            </tr>
            <tr>
                <td>$</td>
                <td>E</td>
            </tr>
            <tr>
                <td>@</td>
                <td>F</td>
            </tr>
            <tr>
                <td>></td>
                <td>G</td>
            </tr>
            <tr>
                <td>~</td>
                <td>H</td>
            </tr>
            <tr>
                <td>*</td>
                <td>I</td>
            </tr>
            <tr>
                <td>}</td>
                <td>J</td>
            </tr>
            <tr>
                <td>/</td>
                <td>K</td>
            </tr>
            <tr>
                <td>;</td>
                <td>L</td>
            </tr>
            <tr>
                <td>(</td>
                <td>M</td>
            </tr>
            <tr>
                <td>&</td>
                <td>N</td>
            </tr>
            <tr>
                <td>
                    <</td>
                <td>O</td>
            </tr>
            <tr>
                <td>:</td>
                <td>P</td>
            </tr>
            <tr>
                <td>)</td>
                <td>Q</td>
            </tr>
            <tr>
                <td>{</td>
                <td>R</td>
            </tr>
            <tr>
                <td>`</td>
                <td>S</td>
            </tr>
            <tr>
                <td>%</td>
                <td>T</td>
            </tr>
            <tr>
                <td>|</td>
                <td>U</td>
            </tr>
            <tr>
                <td>!</td>
                <td>V</td>
            </tr>
            <tr>
                <td>+</td>
                <td>W</td>
            </tr>
            <tr>
                <td>-</td>
                <td>X</td>
            </tr>
            <tr>
                <td>^</td>
                <td>Y</td>
            </tr>
            <tr>
                <td>?</td>
                <td>Z</td>
            </tr>
        </table>
        <input type="text" class="answer default-button2" placeholder="Enter Message">
        <input type="button" class="submit default-button2" value="Submit">
        <p class="revealed hidden"></p>
    </div>
    <?php }?> 

    <div class="completed <?php echo $stage>=1?"":"hidden" ?>">
        <p class="desc">You have completed the What's the Password? Scavenger Hunt. You have earned <span
                class="points"></span> points.</p>
        <input type="button" class="default-button2" value="Return to Scavenger Hunt"
            onclick="window.location.href = 'index.php?filename=scavengerhunt'">
    </div>
    <script>
        var curSong = <?php echo $stage; ?>;
    </script>
    <script src="//unpkg.com/string-similarity/umd/string-similarity.min.js"></script>
    <script src="static/js/code.js"></script>