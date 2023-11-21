const nominateButton = document.querySelectorAll('.nominate-button');
const nominationPopup = document.querySelector('.nomination');
const nominateUserButton = nominationPopup.querySelector('.nominate');
var nominationSection = -1;
const idToSection = {
    "pic": 0,
    "2": 1,
    "3": 2,
}
const sectionToId = {};
for (const key in idToSection) {
    sectionToId[idToSection[key]] = key;
}
var guestData;

var loaded = false;

async function nominateButtonPressed(){
    await fetchGuestData();
    const parentId = this.parentElement.parentElement.id;
    nominationSection = idToSection[parentId];
    await initaliseClassSelector(nominationSection);
    nominationPopup.classList.remove('hidden');

}

function hidePopup(){
    nominationPopup.classList.add('hidden');
}
async function fetchGuestData(){
    if (guestData) return;
    guestData = await getRequest("backend/Votings/guestData.php")
    return true;
}

async function initaliseClassSelector(section){
    const classSelector = nominationPopup.querySelector('.class');
    for (var classOf in guestData){
        const option = document.createElement("option");
        option.value = classOf;
        option.innerText = classOf;
        classSelector.appendChild(option);
    }
    classSelector.addEventListener('change', function(){
        initaliseNameSelector(nominationSection,this.value);
    })
    loaded = true;

}
function initaliseNameSelector(section,classOf){
    const nameSelector = nominationPopup.querySelector('.name');
    const classSelector = nominationPopup.querySelector('.class');
    nameSelector.innerHTML = "";
    const option = document.createElement("option");
    option.value = "";
    option.innerText = "Please Select a Student";
    guestData[classOf].forEach(([guestId,name]) => {
        const option = document.createElement("option");
        option.value = guestId;
        option.innerText = name;
        if (nomineesData[section][guestId]) option.setAttribute("disabled", true);
        nameSelector.appendChild(option);
    });
    classSelector.value = classOf;
}

function updateNominees(section, nominee){
    const nomineeContainer = document.querySelector(`#${sectionToId[section]}`).querySelector('.nominee__container');
    const nomineeElement = document.createElement("div");
    nomineeElement.classList.add("nominee");
    nomineeElement.innerHTML = `
        <img class="nominee__img" src="${nominee["image"]}">
        <p class="nominee__name">${nominee["studentName"]}</p>
        <p class="nominee__table">${nominee["class"]}</p>
    `;
    // put at the front of the div
    nomineeContainer.insertBefore(nomineeElement, nomineeContainer.firstChild);

    nomineesData[section][nominee.guestId] = nominee;

}


async function nominateUser(){
    const classOf = nominationPopup.querySelector('.class').value;
    const nameSelector = nominationPopup.querySelector('.name');
    const guestId = nameSelector.value;
    const descriptionSelect = nominationPopup.querySelector('.reason');
    const description = descriptionSelect.value;
    const imageUpload = nominationPopup.querySelector('.image');
    const image = imageUpload.files[0];
    if (guestId == "" || description == ""){
        alert("Please fill in all fields");
        return;
    }
    var response = await postRequest("backend/Votings/nominate.php", {guestId: guestId, section: nominationSection, nomineeDesc: description, image: image},
        null,true,true);
    console.log(response)
    if (response.success){
        hidePopup();
        updateNominees(nominationSection,{
        "guestId":guestId,
        "class":classOf, 
        "image":image,
        "description":description,
        "studentName":nameSelector.options[nameSelector.selectedIndex].text});
        nominationSection = -1;
    }
    else{
        alert(response.error);
    }
}
for (let i = 0; i < nominateButton.length; i++){
    nominateButton[i].addEventListener('click', nominateButtonPressed);
}

nominationPopup.addEventListener('click', function(event){
    if (event.target === nominationPopup){
        hidePopup();
    }
});

nominateUserButton.addEventListener('click', nominateUser);