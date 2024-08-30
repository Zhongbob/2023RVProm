const answerSubmit = document.body.querySelector(".submit")
const answerInput = document.body.querySelector(".answer")
const completedDiv = document.body.querySelector(".completed")
const mainDiv = document.body.querySelector(".main")
function filterInput(input){
    return input.toLowerCase().replaceAll(" ","");
}
async function answer(){
    if (filterInput(answerInput.value) != "voxvincit"){
        alert("Incorrect Answer!")
    }
    else{
        var response = await postRequest("backend/ScavengerHunt/quiz.php",{
            scavengerHuntId:7,
            correct:true
        });
        if (response.success){
            completedDiv.classList.remove("hidden")
            mainDiv.classList.add("hidden")
        }
    }
}

answerSubmit.onclick = answer

