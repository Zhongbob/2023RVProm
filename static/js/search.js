const search = document.querySelectorAll('.search');

function filterNames(){
    const name = this.value.toLowerCase();
    const nomineeContainer = this.parentElement.querySelectorAll('.nominee');
    nomineeContainer.forEach(nominee => {
        const nameText = nominee.querySelector(".nominee__name").innerText.toLowerCase();
        const classText = nominee.querySelector(".nominee__table").innerText.toLowerCase();
        if (nameText.indexOf(name) > -1 || classText.indexOf(name) > -1){
            nominee.classList.remove('hidden');
        } else {
            nominee.classList.add('hidden');
        }
    })
}

for (var i = 0; i < search.length; i++){
    search[i].addEventListener('input', filterNames);
}