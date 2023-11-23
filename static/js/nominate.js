const nominateButton = document.querySelectorAll('.nominate-button');
const nominationPopup = document.querySelector('.nomination');
const nominateUserButton = nominationPopup.querySelector('.nominate');
const guestList = nominationPopup.querySelector('.guest-list');
const nameInput = nominationPopup.querySelector('.name');
const cancelButtons = nominationPopup.querySelectorAll('.cancel');
var classSelector = nominationPopup.querySelector('.class');
classSelector = customSelect(classSelector)[0];
console.log(classSelector)
var currentClass = "";

var nominationSection = -1;
const idToSection = {
    "pic": 0,
    "pk": 1,
    "pq": 2,
}
const sectionToId = {};
for (const key in idToSection) {
    sectionToId[idToSection[key]] = key;
}
var guestData;

var loaded = false;

var guestId = -1;

async function nominateButtonPressed(){
    
    if (!loggedIn){
        nominationPopup.classList.remove('hidden');
        nominationPopup.querySelector('#main').classList.add('hidden');
        return
    }
    await fetchGuestData();
    const parentId = this.parentElement.parentElement.id;
    const nominationCategory = nominationPopup.querySelector('.nomination-type');
    const remainingCount = nominationPopup.querySelector('.remaining span');
    nominationCategory.innerText = this.parentElement.querySelector("h2").innerText;
    nominationSection = idToSection[parentId];
    
    if (remainingVotes[nominationSection] <= 0){
        nominationPopup.querySelector('#main').classList.add('hidden');
        nominationPopup.querySelector('#max').classList.remove('hidden');
    }
    else {
        nominationPopup.querySelector('#main').classList.remove('hidden');
        nominationPopup.querySelector('#max').classList.add('hidden');
    }
    remainingCount.innerText = remainingVotes[nominationSection];
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
    for (var classOf in guestData){
        const option = document.createElement("option");
        option.value = classOf;
        option.innerText = classOf;
        classSelector.append(option);
    }
    initaliseNameSelector(section);
    classSelector.select.addEventListener('change', function(){
        currentClass = this.value;
        filterByClass(this.value);
    })
    loaded = true;

}
function guestClicked(){
    guestId = this.dataset.guestId;
    const nameSelector = nominationPopup.querySelector('.name');
    nameSelector.value = this.dataset.name;
    classSelector.value = this.dataset.class;
    guestList.classList.add('hidden');
}
function addGuest(guestId, name, classOf, nominated){
    const option = document.createElement("div");
    option.classList.add("guest");
    const studentDiv = document.createElement("p");
    studentDiv.classList.add("guest__name");
    studentDiv.innerText = name;
    const classDiv = document.createElement("p");
    classDiv.classList.add("guest__class");
    classDiv.innerText = classOf;
    option.appendChild(studentDiv);
    option.appendChild(classDiv);
    option.dataset.guestId = guestId;
    option.dataset.class = classOf;
    option.dataset.name = name;
    if (nominated) option.classList.add("disabled");
    else{
        option.addEventListener('click', guestClicked);
    }
    guestList.appendChild(option);
}
function initaliseNameSelector(section){
    const guestList = nominationPopup.querySelector('.guest-list');
    guestList.innerHTML = "";
    for (const classOf in guestData){
        guestData[classOf].forEach(([guestId,name]) => {
            addGuest(guestId,name,classOf,nomineesData[section][guestId]);
        });
    }
}


function filterByClass(classOf){
    const guests = nominationPopup.querySelectorAll('.guest');
    for (const guest of guests){
        if ((guest.dataset.name.toLowerCase().includes(nameInput.value.toLowerCase()) || nameInput.value == "") &&(guest.dataset.class == classOf || classOf == "")){
            guest.classList.remove('hidden');
        }
        else{
            guest.classList.add('hidden');
        }
    }
}

function updateNominees(section, nominee){
    const nomineeContainer = document.querySelector(`#${sectionToId[section]}`).querySelector('.nominee__container');
    const nomineeElement = document.createElement("div");
    nomineeElement.classList.add("nominee");
    
    let imgElement = document.createElement('img');
imgElement.src = URL.createObjectURL(nominee["image"]);
imgElement.classList.add('nominee__img');

nomineeElement.innerHTML = `
        <div class="nominee-img__container">

        ${imgElement.outerHTML}
        <p class="nominee__desc">
        ${nominee["description"]?nominee["description"]:""}
        </p>
        <div class = "heart"></div>
        </div>
        <p class = "nominee__name">${nominee["studentName"]}</p>
        <p class = "nominee__table">${nominee["class"]}</p>
    `;
if (nominee.voted) nomineeElement.classList.add('voted');
nomineeElement.dataset.guestId = nominee.guestId;

nomineeElement.addEventListener('click', vote);

// put at the front of the div
nomineeContainer.insertBefore(nomineeElement, nomineeContainer.firstChild);
    remainingVotes[section]--;
    const remainingVotesElement = document.querySelector(`#${sectionToId[section]} .remaining-votes>span`);
    remainingVotesElement.innerText = remainingVotes[section];
nomineesData[section][nominee.guestId] = nominee;


}


async function nominateUser(){
    const classOf = nominationPopup.querySelector('.class').value;
    const nameSelector = nominationPopup.querySelector('.name');
    const descriptionSelect = nominationPopup.querySelector('.reason');
    const description = descriptionSelect.value;
    const imageUpload = nominationPopup.querySelector('#image-upload');
    const image = imageUpload.files?imageUpload.files[0]:undefined;
    if (guestId == -1){
        alert("Please fill in all fields. Ensure you have clicked on a student from the dropdown list.");
        return;
    }
    console.log(description)
    console.log(imageUpload.files)
    if (description == "" || image == undefined){
        var res = confirm("Warning: You have not filled in an image or description. You may still vote for this guest, and it will be counted, but it will not be displayed on the website. Would you like to proceed?")
        if (!res) return;
        voteUser(guestId, nominationSection, 1);
        guestId = -1;
        hidePopup();
        return;
    }
    var response = await postRequest("backend/Votings/nominate.php", {guestId: guestId, section: nominationSection, nomineeDesc: description, image: image},
        null);
    console.log(response)
    if (response.success){
        hidePopup();
        updateNominees(nominationSection,{
        "guestId":guestId,
        "class":classOf, 
        "image":image,
        "description":description,
        "studentName":nameSelector.value,
        "voted":true});
        nominationSection = -1;
        guestId = -1;
    }
    else{
        alert(response.error);
    }
}

function filterByName(name){
    const guests = nominationPopup.querySelectorAll('.guest');
    for (const guest of guests){
        if ((guest.dataset.name.toLowerCase().includes(name.toLowerCase()) || name == "") && (guest.dataset.class == currentClass || currentClass == "")){
            guest.classList.remove('hidden');
        }
        else{
            guest.classList.add('hidden');
        }
    }
}

function debounce(func, wait) {
    let timeout;

    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(this.value);
        };

        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};


for (let i = 0; i < nominateButton.length; i++){
    nominateButton[i].addEventListener('click', nominateButtonPressed);
}


nominateUserButton.addEventListener('click', nominateUser);

nameInput.addEventListener('input', debounce(filterByName, 500));

cancelButtons.forEach((btn)=>btn.addEventListener('click', hidePopup));

nameInput.addEventListener('focus', function(){
    guestList.classList.remove('hidden');
})

nominationPopup.addEventListener('click', function(e){
    const targetClasses = ["guest__name", "guest__class", "guest", "guest-list", "class", "name"];
    const isTargetValid = targetClasses.some(className => e.target.classList.contains(className));
    if (!isTargetValid) {
        guestList.classList.add('hidden');
    }
});

