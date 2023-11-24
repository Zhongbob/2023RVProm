const nominees = document.querySelectorAll('.nominee');

async function voteUser(guestId,section,voteRemove){
    const response = await postRequest("backend/Votings/voting.php", {
        "guestId": guestId,
        "vote": voteRemove,
        "section": section
    });
    if (!response.success){
        alert(response.error);
        return false;
    }
    if (voteRemove == 1){
        remainingVotes[section]--;
    }
    else{
        remainingVotes[section]++;
    }
    const remainingVotesElement = document.querySelector(`#${sectionToId[section]} .remaining-votes>span`);
    remainingVotesElement.innerText = remainingVotes[section];
    return true;
}
async function vote(){
    this.classList.toggle('voted');
    if (this.classList.contains('voted')){
        const success = await voteUser(this.dataset.guestId, idToSection[this.parentElement.parentElement.id], 1);
        if (!success){
            this.classList.remove('voted');
        }
    }
    else {
        const success = await voteUser(this.dataset.guestId, idToSection[this.parentElement.parentElement.id], 0);
        if (!success){
            this.classList.add('voted');
        }
    }
}

for (var nominee of nominees){
    nominee.addEventListener('click', vote);
}