const continueButton = document.querySelector(".continue");
const classSelector = document.querySelector(".class-selector");
continueButton.addEventListener("click", () => {
  const warningContainer = document.querySelector(".warning__container");
  warningContainer.classList.add("hidden");
  const mainContainer2 = document.querySelector(".main2");
  mainContainer2.classList.remove("hidden");
});

function initialiseSelectors(classOf){
    const classStudents = guestData[classOf];
    const nameSelector = document.querySelector(".name-selector");
    nameSelector.innerHTML = "";
    console.log(guestData,classOf)
    classStudents.forEach(([guestId,name]) => {
        const option = document.createElement("option");
        option.value = guestId;
        option.innerText = name;
        nameSelector.appendChild(option);
    });
    classSelector.value = classOf;

}

classSelector.addEventListener("change", (e) => {
    initialiseSelectors(e.target.value);
});

const submit2Button = document.querySelector(".submit2");
submit2Button.addEventListener("click", () => {
  const nameSelector = document.querySelector(".name-selector");
  const guestId = nameSelector.value;
  postRequest("backend/Login/assignGuest.php", { guestId: guestId, confirmed: false }, (result) => {
    console.log(result)
    if (result.success) {
      window.location.href = "index.php";
    } else {
      alert("Error: " + result.error)
    }
}, true, true)
})

const submit1Button = document.querySelector(".submit1");
submit1Button.addEventListener("click", async () => {
  if (guestId == -1) {
    alert("Please select a user");
    return}
  var response = await postRequest("backend/Login/assignGuest.php", { guestId: guestId, confirmed: true })
  console.log(response)
  if (response.success) {
    window.location.href = "index.php";
  } else {
    alert("Error: " + response.error)
  }

});

const guestButtons = document.querySelectorAll(".guest");
guestButtons.forEach((button) => {
  button.addEventListener("click", () => {

    guestId = button.dataset.guestId;
    console.log(guestId);
    for (var i = 0; i < guestButtons.length; i++) {
      guestButtons[i].classList.remove("selected");
    }
    button.classList.add("selected");

  });
});

initialiseSelectors(classSelector.value)

const nameNotHereButton = document.querySelector(".error");
nameNotHereButton.addEventListener("click", () => {
  const mainContainer1 = document.querySelector(".main");
  mainContainer1.classList.add("hidden");
  const mainContainer2 = document.querySelector(".main2");
  mainContainer2.classList.remove("hidden");
});