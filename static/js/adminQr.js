var resultContainer = document.getElementById('qr-reader-results');
var lastResult, countResults = 0;
const firstGuest = document.body.querySelector(".guest-info__container");
const submitButton = document.body.querySelector(".submit-button");


async function onScanSuccess(decodedText, decodedResult) {
    if (decodedText !== lastResult) {
        ++countResults;
        lastResult = decodedText;
        // Handle on success condition with the decoded message.
        if (!decodedText.includes(":")){ 
            alert("Invalid QR Code");
            return
        }
        var [guestId,code] = decodedText.split(":");
        var response = await getRequest("backend/Admin/checkCode.php",{code,guestId});
        if (!response.success){
            alert(response.error);
        }
        else{
            alert(`Successfully registered ${response.name}`);
        }
    }
}

function initaliseNameSelector() {
    const guestList = firstGuest.querySelector(".guest-list");
    guestList.innerHTML = "";
    for (const guestId in guestData) {
      const guest = guestData[guestId];  
      addGuest(
        guestId,
        guest["studentName"],
        guest["class"],
      );
    }
  }
  function guestClicked(whichGuest, guest) {
    whichGuest.dataset.guestId = guest.guestId;
    const nameSelector = whichGuest.querySelector(".name");
    const guestList = whichGuest.querySelector(".guest-list");
    nameSelector.value = `${guest.name} (${guest.class})`;
    guestList.classList.add("hidden");
  }
  function toHide(nameInput, guest) {
    return (
      guest.dataset.name.toLowerCase().includes(nameInput.value.toLowerCase()));
  }

  function addGuest(guestId, name, classOf, nominated) {
    const guestList = firstGuest.querySelector(".guest-list");
  
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
    guestList.appendChild(option);
  }

  function filter(whichGuest) {
    const guests = whichGuest.querySelectorAll(".guest");
    const nameInput = whichGuest.querySelector(".name");
    for (const guest of guests) {
      if (toHide(nameInput, guest)) {
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

  function configureNameInputs(whichGuest) {
    const nameInput = whichGuest.querySelector(".name");
    const guestList = whichGuest.querySelector(".guest-list");
    nameInput.addEventListener("input", debounce(()=>filter(whichGuest), 500));
    nameInput.addEventListener("focus", function () {
      guestList.classList.remove("hidden");
    });
  }

var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, aspectRatio:1.333334 });
html5QrcodeScanner.render(onScanSuccess);
initaliseNameSelector();
configureNameInputs(firstGuest);

async function submitAttendance(){
    const guestId = firstGuest.dataset.guestId;
    var response = await postRequest("backend/Admin/markAttendance.php",{guestId});
    if (!response.success){
        alert(response.error);
    }
    else{
        const name = response.name;
        const tableNo = response.tableNo;
        alert(`Successfully registered ${name}`);
        const nameInput = firstGuest.querySelector(".name");
        nameInput.value = "";
        if (response.signedUp && !response.fromrvhs){
          const verify = confirm(`This guest (${response.email}) has yet to be verified. Table Number ${tableNo}. Verify him/her?`)
          if (verify){
            var response = await postRequest("backend/Admin/verifyGuest.php",{guestId},null,true,true);
            if (!response.success){
              alert(response.error);
            }
            else{
              alert(`Successfully verified ${name}. Table Number ${tableNo}`);
            }
          }
        }
        else if (!response.signedUp){
          alert(`This guest has yet to sign up. Table Number ${tableNo}.`)
        }
    }
}
submitButton.addEventListener("click",submitAttendance)

document.body.addEventListener("click", function (e) {
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
        const guestList = firstGuest.querySelector(".guest-list");
    guestList.classList.add("hidden");
    }
  });