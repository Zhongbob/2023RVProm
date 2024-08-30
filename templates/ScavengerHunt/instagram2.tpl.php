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
    body form{
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
<h1>Instagram 2!</h1>
<div class = 'main <?php echo $instagramCompleted?"hidden":"" ?>'>
<p class = "desc">Take a photo of you and your friends exploring the prom venue at these locations! Post your photos onto instagram with the hashtag <br> <b>#rvprom2023!</b></p>
<div class = "images">
    <img src = "">
</div>
<p class = "desc">Once you've posted your picture, submit the link to the instagram post below!</p>

<form id = "pose" method="post">
    <input class = "default-button2"  type="text" name="poseLink" placeholder="Instagram Link" required />
    <input class = "default-button2" type="submit" value="Submit" >
</form>
</div>

<div class = "completed <?php echo $instagramCompleted?"":"hidden" ?>">
    <p class = "desc">You have completed the Instagram Scavenger Hunt. You have earned <span class = "points"></span> points.</p>
    <input type = "button" class = "default-button2" value = "Return to Scavenger Hunt" onclick = "window.location.href = 'index.php?filename=scavengerhunt'">
</div>
<script>
    const form = document.getElementById("pose");
    form.addEventListener("submit",async function(e){
        e.preventDefault();
        var result = await postRequest("backend/ScavengerHunt/instagram.php",{
            "link":form.poseLink.value,
            "scavengerHuntId":6,
        },null,true,true);
        if (result.success){
            document.querySelector(".main").classList.add("hidden");
            document.querySelector(".completed").classList.remove("hidden");
        } else {
            alert(result.error);
        }
    })
</script>

