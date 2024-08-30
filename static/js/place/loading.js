const loadingScreen = document.querySelector('.loading'); 
const startBtn = document.querySelector('.start');
setupStage()
startBtn.onclick = function(){
    document.querySelector(".loading").classList.add("fade-out");
    document.querySelector(".loading").addEventListener("animationend", () => {
        document.querySelector(".loading").style.display = "none";
    })
}