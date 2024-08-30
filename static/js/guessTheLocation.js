const startDiv = document.querySelector(".start")
const startBtn = startDiv.querySelector("input")
const songDiv = document.querySelector(".song")
const imageEle = songDiv.querySelector(".place")
const answerRevealed = songDiv.querySelector(".revealed")
const answerSubmit = songDiv.querySelector(".submit")
const answerInput = songDiv.querySelector(".answer")
const completedDiv = document.querySelector(".completed")
const songData = [
    {actualAnswer: "Big E", answer:["bige"]},
    {actualAnswer: "MLA Room", answer:["mlaroom","mla","damian","chee","mrchee","mrdamian"]},
    {actualAnswer: "WHITESPACE", answer:["whitespace","white space"]},

]
var alreadyAnswered = true;

function startGame(){
    startDiv.classList.add('hidden')
    songDiv.classList.remove("hidden")
    imageEle.src = `static/assets/scavengerHunt/Places/${curSong+1}.jpg`
    alreadyAnswered = false;

}
function isCorrect(input,threshold){
    threshold = threshold ?? 0.8
    input = filterInput(input)
    for (var answer of songData[curSong].answer){
        const similarity = stringSimilarity.compareTwoStrings(input,answer)
        if (similarity > threshold){
            return true;
        }
    }
    return false;
}
function filterInput(input){
    return input.toLowerCase().replace(/[^a-zA-Z]/g, "");
}
async function answer(){
    alreadyAnswered = true;
    answerRevealed.classList.remove("hidden")
    var correct = true;
    if (isCorrect(answerInput.value)){
        answerRevealed.innerText = "(Correct!) The answer is " + songData[curSong].actualAnswer
    }
    else{
        answerRevealed.innerText = "(Wrong!) The answer is " + songData[curSong].actualAnswer
        correct = false;
    }
    var response = await postRequest("backend/ScavengerHunt/quiz.php",{
        scavengerHuntId:8,
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
        imageEle.src = `static/assets/scavengerHunt/Places/${curSong+1}.jpg`
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

