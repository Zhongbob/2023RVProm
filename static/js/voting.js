const nominees = document.querySelectorAll('.nominee');
async function vote(){
    this.classList.toggle('voted');
    if (this.classList.contains('voted')){
        var response = await postRequest("backend/Votings/voting.php", {
            "guestId": this.dataset.guestId,
            "vote": 1,
            "section": idToSection[this.parentElement.parentElement.id] 
        });
        console.log(response);
        if (!response.success){
            this.classList.remove('voted');
            alert(response.error)
        }
    }
    else {
        var response = await postRequest("backend/Votings/voting.php", {
            "guestId": this.dataset.guestId,
            "vote": 0,
            "section": idToSection[this.parentElement.parentElement.id] 
        });
        if (!response.success){
            this.classList.add('voted');
            alert(response.error)
        }
    }
}

for (var nominee of nominees){
    nominee.addEventListener('click', vote);
}