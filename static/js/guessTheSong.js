const startDiv = document.querySelector(".start")
const startBtn = startDiv.querySelector("input")
const songDiv = document.querySelector(".song")
const songEle = songDiv.querySelector("audio")
const sourceEle = songEle.querySelector("source")
const answerRevealed = songDiv.querySelector(".revealed")
const answerSubmit = songDiv.querySelector(".submit")
const answerInput = songDiv.querySelector(".answer")
const completedDiv = document.querySelector(".completed")
const songData = [
    {answer:["立化美女","lihuameinu","lihuameinv"]},
    {answer:["立化情","lihuaqing","lihuaqin"]},
    {answer:["立化忆","lihuayi","lihuayi"]},
]
var alreadyAnswered = true;

function startGame(){
    startDiv.classList.add('hidden')
    songDiv.classList.remove("hidden")
    sourceEle.src = `static/assets/scavengerHunt/Songs/${curSong+1}.mp3`
    songEle.load()
    songEle.play()
    alreadyAnswered = false;

}
function filterInput(input){
    return input.toLowerCase().replaceAll(" ","");
}
async function answer(){
    alreadyAnswered = true;
    songEle.pause()
    answerRevealed.classList.remove("hidden")
    var correct = true;
    if (songData[curSong].answer.includes(filterInput(answerInput.value))){
        answerRevealed.innerText = "(Correct!) The answer is " + songData[curSong].answer[0]
    }
    else{
        answerRevealed.innerText = "(Wrong!) The answer is " + songData[curSong].answer[0]
        correct = false;
    }
    var response = await postRequest("backend/ScavengerHunt/quiz.php",{
        scavengerHuntId:1,
        correct
    });
    if (response.success){
        curSong = response.stage;
    }
}

function nextQuestion(){
    answerRevealed.classList.add("hidden")
    answerSubmit.value = "Submit"
    answerInput.value = ""
    answerRevealed.innerText = ""
    if (curSong < songData.length){
        sourceEle.src = "static/assets/scavengerHunt/Songs/" + (curSong + 1) + ".mp3"
        songEle.load()
        songEle.play()
        alreadyAnswered = false;
    }
    else{
        songDiv.classList.add("hidden")
        completedDiv.classList.remove("hidden")
    }
}

startBtn.addEventListener("click",startGame)
answerSubmit.onclick = function(){
    if (!alreadyAnswered){
        answer()
        answerSubmit.value = "Next"
    }
    else{
        nextQuestion()
    }
}

