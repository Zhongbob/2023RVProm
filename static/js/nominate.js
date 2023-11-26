const nominateButton = document.querySelectorAll(".nominate-button");
const nominationPopup = document.querySelector(".nomination");
const nominateUserButton = nominationPopup.querySelector(".nominate");
const firstGuest = nominationPopup.querySelector(".first-guest");
const secondGuest = nominationPopup.querySelector(".second-guest");
// const nameInput = nominationPopup.querySelector('.name');
const cancelButtons = nominationPopup.querySelectorAll(".cancel");
const guestLists = nominationPopup.querySelectorAll(".guest-list");
// var classSelector = nominationPopup.querySelector('.class');
// var currentClass = "";
var nominationSection = -1;
const idToSection = {
  pic: 0,
  pk: 1,
  pq: 2,
};
const sectionToId = {};
for (const key in idToSection) {
  sectionToId[idToSection[key]] = key;
}
var loaded = false;
var guestId = -1;

async function nominateButtonPressed() {
  if (!loggedIn) {
    nominationPopup.classList.remove("hidden");
    nominationPopup.querySelector("#main").classList.add("hidden");
    return;
  }
  await fetchGuestData();
  const parentId = this.parentElement.parentElement.id;
  const nominationCategory = nominationPopup.querySelector(".nomination-type");
  const remainingCount = nominationPopup.querySelector(".remaining span");
  nominationCategory.innerText =
    this.parentElement.querySelector("h2").innerText;
  nominationSection = idToSection[parentId];

  if (remainingVotes[nominationSection] <= 0) {
    nominationPopup.querySelector("#main").classList.add("hidden");
    nominationPopup.querySelector("#max").classList.remove("hidden");
  } else {
    nominationPopup.querySelector("#main").classList.remove("hidden");
    nominationPopup.querySelector("#max").classList.add("hidden");
  }
  if (nominationSection == 0) {
    secondGuest.classList.remove("hidden");

  }
  else {
    secondGuest.classList.add("hidden");
  }
  remainingCount.innerText = remainingVotes[nominationSection];
  await initaliseClassSelector(nominationSection);
  nominationPopup.classList.remove("hidden");
}

function hidePopup() {
  nominationPopup.classList.add("hidden");
}
async function fetchGuestData() {
  if (guestData) return;
  guestData = await getRequest("backend/Votings/guestData.php");
  return true;
}

async function initaliseClassSelector(section) {
  const classSelector1 = firstGuest.querySelector(".class").classInput;
  const classSelector2 = secondGuest.querySelector(".class").classInput;
  const uniqueClasses = new Set();
  for (const guestId in guestData) {
    const guest = guestData[guestId];
    uniqueClasses.add(guest["class"]);
  }
  const classList = Array.from(uniqueClasses);
  classList.sort();
  for (const classOf of classList) {
    const option = document.createElement("option");
    option.value = classOf;
    option.innerText = classOf;
    classSelector1.append(option);
    classSelector2.append(option.cloneNode(true));
  }
  initaliseNameSelector(section);
  loaded = true;
}
function guestClicked(whichGuest, guest) {
  whichGuest.dataset.guestId = guest.guestId;
  const nameSelector = whichGuest.querySelector(".name");
  const classSelector = whichGuest.querySelector(".class").classInput;
  const guestList = whichGuest.querySelector(".guest-list");
  nameSelector.value = guest.name;
  classSelector.value = guest.class;
  guestList.classList.add("hidden");
  disableGuests(whichGuest);
}
function addGuest(guestId, name, classOf, nominated) {
  const guestList1 = firstGuest.querySelector(".guest-list");
  const guestList2 = secondGuest.querySelector(".guest-list");

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
  else {
    option.addEventListener("click", () =>
      guestClicked(firstGuest, {
        guestId: guestId,
        name: name,
        class: classOf,
      })
    );
  }
  guestList1.appendChild(option);
  const option2 = option.cloneNode(true);
  option2.addEventListener("click", () =>
    guestClicked(secondGuest, {
      guestId: guestId,
      name: name,
      class: classOf,
    })
  );
  guestList2.appendChild(option2);
}
function initaliseNameSelector(section) {
  const guestList1 = firstGuest.querySelector(".guest-list");
  const guestList2 = secondGuest.querySelector(".guest-list");
  guestList1.innerHTML = "";
  guestList2.innerHTML = "";
  for (const guestId in guestData) {
    const guest = guestData[guestId];
    var alreadyNominated = false;

    addGuest(
      guestId,
      guest["studentName"],
      guest["class"],
      section == 0 ? false : nomineesData[section][guestId]
    );
  }
}

function updateNominees(section, nominee) {
  const nomineeContainer = document
    .querySelector(`#${sectionToId[section]}`)
    .querySelector(".nominee__container");
  const nomineeElement = document.createElement("div");
  nomineeElement.classList.add("nominee");

  let imgElement = document.createElement("img");
  imgElement.src = URL.createObjectURL(nominee["image"]);
  imgElement.classList.add("nominee__img");
if (section == 0){
  nomineeElement.innerHTML = `
        <div class="nominee-img__container">
        ${imgElement.outerHTML}
        <p class="nominee__desc">
        ${nominee["description"] ? nominee["description"] : ""}
        </p>
        <div class = "heart"></div>
        </div>
        <p class = "nominee__name">${nominee["studentName1"]} & ${nominee["studentName2"]}</p>
        <p class = "nominee__table">${nominee["class1"]} || ${nominee["class2"]}</p>
        `
}
  nomineeElement.innerHTML = `
        <div class="nominee-img__container">

        ${imgElement.outerHTML}
        <p class="nominee__desc">
        ${nominee["description"] ? nominee["description"] : ""}
        </p>
        <div class = "heart"></div>
        </div>
        <p class = "nominee__name">${nominee["studentName1"]}</p>
        <p class = "nominee__table">${nominee["class1"]}</p>
    `;
  if (nominee.voted) nomineeElement.classList.add("voted");
  nomineeElement.dataset.guestId1 = nominee.guestId1;
  nomineeElement.dataset.guestId2 = nominee.guestId2

  nomineeElement.addEventListener("click", vote);

  // put at the front of the div
  nomineeContainer.insertBefore(nomineeElement, nomineeContainer.firstChild);
  remainingVotes[section]--;
  const remainingVotesElement = document.querySelector(
    `#${sectionToId[section]} .remaining-votes>span`
  );
  remainingVotesElement.innerText = remainingVotes[section];
  nomineesData[section][nominee.guestId] = nominee;
}

function resetPopup() {
  const nameSelector1 = firstGuest.querySelector(".name");
  const nameSelector2 = secondGuest.querySelector(".name");
  const descriptionSelect = nominationPopup.querySelector(".reason");
  const imageUpload = nominationPopup.querySelector("#image-upload");
  imageUpload.value = "";
  descriptionSelect.value = "";
  nameSelector1.value = "";
  nameSelector2.value = "";
  imagePreview.src = "";
  imageText.innerHTML =
    "Upload Image of Student! <br> Recommended Aspect Ratio is 2:3";
}
async function nominateUser() {
  const classOf1 = firstGuest.querySelector(".class").value;
  const name1 = firstGuest.querySelector(".name");
  const classOf2 = secondGuest.querySelector(".class").value;
  const name2 = secondGuest.querySelector(".name");
  const guestId1 = firstGuest.dataset.guestId;
  const guestId2 = secondGuest.dataset.guestId;
  const descriptionSelect = nominationPopup.querySelector(".reason");
  const description = descriptionSelect.value;
  const imageUpload = nominationPopup.querySelector("#image-upload");
  var image = imageUpload.files ? imageUpload.files[0] : undefined;
  if (guestId1 == "") {
    alert(
      "Please fill in all fields. Ensure you have clicked on a student from the dropdown list."
    );
    return;
  }
  if (sectionToId[nominationSection] == "pic") {
    if (guestId2 == "") {
      alert(
        "Please fill in all fields. Ensure you have clicked on a student from the dropdown list."
      );
      return;
    }
    if (guestId1 == guestId2) {
      alert("You cannot nominate the same student twice!");
      return;
    }
  }
  if (description == "" || image == undefined) {
    var res = confirm(
      "Warning: You have not filled in an image or description. You may still vote for this guest, and it will be counted, but it will not be displayed on the website. Would you like to proceed?"
    );
    
    
    if (!res) return;
    voteUser(guestId, nominationSection, 1);
    guestId = -1;
    hidePopup();
    return;
  }
  console.log(image)
  image = await compressImage(image, 300, 500)
  console.log(image)
  onLoading();
  var formData = convertToFormData({
    guestId1: guestId1,
    guestId2: guestId2,
    section: nominationSection,
    nomineeDesc: description,
  });
  formData.append("image", image, `${name1.value}${name2.value}.jpg`);

  var response = await postRequest(
    "backend/Votings/nominate.php",
    formData
    ,
    null
  );
  console.log(response);
  if (response.success) {
    hidePopup();
    updateNominees(nominationSection, {
      guestId1: guestId1,
      class1: classOf1,
      guestId2: guestId2,
      class2: classOf2,
      image: image,
      studentName1: name1.value,
      studentName2: name2.value,
      description: description,
      voted: true,
    });
    onSuccess();
    nominationSection = -1;
    guestId = -1;
    resetPopup();
  } else {
    onErrorMessage(response.error);
  }
}

function toHide(nameInput, classInput, guest) {
  return (
    guest.dataset.name.toLowerCase().includes(nameInput.value.toLowerCase()) &&
    (guest.dataset.class == classInput.value || classInput.value == "")
  );
}
function disableGuests(whichGuest) {
  if (nominationSection != 0) return
  const otherGuest = whichGuest == firstGuest ? secondGuest : firstGuest;
  const guests = otherGuest.querySelectorAll(".guest");
  const curGuestId = whichGuest.dataset.guestId;
  console.log(nomineesData)
  for (const guest of guests) {
    if (
      (nomineesData[nominationSection][`${curGuestId}-${guest.dataset.guestId}`] ||
      nomineesData[nominationSection][`${guest.dataset.guestId}-${curGuestId}`]) ||
      guest.dataset.guestId == curGuestId
    ) {
      guest.classList.add("disabled");
    }
  }
}
function filter(whichGuest) {
  const guests = whichGuest.querySelectorAll(".guest");
  const nameInput = whichGuest.querySelector(".name");
  const classInput = whichGuest.querySelector(".class").classInput;
  for (const guest of guests) {
    if (toHide(nameInput, classInput, guest)) {
      guest.classList.remove("hidden");
    } else {
      guest.classList.add("hidden");
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
}

for (let i = 0; i < nominateButton.length; i++) {
  nominateButton[i].addEventListener("click", nominateButtonPressed);
}

nominateUserButton.addEventListener("click", nominateUser);

function configureNameInputs(whichGuest) {
  const otherGuest = whichGuest == firstGuest ? secondGuest : firstGuest;
  const nameInput = whichGuest.querySelector(".name");
  const classInput = customSelect(whichGuest.querySelector(".class"))[0];
  whichGuest.querySelector(".class").classInput = classInput;
  const guestList = whichGuest.querySelector(".guest-list");
  const otherGuestList = otherGuest.querySelector(".guest-list");
  nameInput.addEventListener("input", debounce(()=>filter(whichGuest), 500));
  classInput.select.addEventListener("change", ()=>filter(whichGuest), );
  nameInput.addEventListener("focus", function () {
    guestList.classList.remove("hidden");
    otherGuestList.classList.add("hidden");
  });
}

cancelButtons.forEach((btn) => btn.addEventListener("click", hidePopup));

nominationPopup.addEventListener("click", function (e) {
  const targetClasses = [
    "guest__name",
    "guest__class",
    "guest",
    "guest-list",
    "class",
    "name",
  ];
  const isTargetValid = targetClasses.some((className) =>
    e.target.classList.contains(className)
  );
  if (!isTargetValid) {
    for (const guestList of guestLists) {
      guestList.classList.add("hidden");
    }
  }
});

configureNameInputs(firstGuest);
configureNameInputs(secondGuest);
