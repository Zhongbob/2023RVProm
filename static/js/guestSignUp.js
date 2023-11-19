const continueButton = document.querySelector(".continue");
continueButton.addEventListener("click", () => {
  const warningContainer = document.querySelector(".warning__container");
  warningContainer.classList.add("hidden");
});

function levenshteinDistance(a, b) {
  const matrix = [];

  // Initialize the matrix
  for (let i = 0; i <= b.length; i++) {
    matrix[i] = [i];
  }
  for (let j = 0; j <= a.length; j++) {
    matrix[0][j] = j;
  }

  // Populate the matrix
  for (let i = 1; i <= b.length; i++) {
    for (let j = 1; j <= a.length; j++) {
      if (b.charAt(i - 1) === a.charAt(j - 1)) {
        matrix[i][j] = matrix[i - 1][j - 1];
      } else {
        matrix[i][j] = Math.min(
          matrix[i - 1][j - 1] + 1, // substitution
          Math.min(
            matrix[i][j - 1] + 1, // insertion
            matrix[i - 1][j] + 1
          )
        ); // deletion
      }
    }
  }

  return matrix[b.length][a.length];
}

function findMostSimilar(targetName, nameList) {
  let minDistance = Infinity;
  let mostSimilar = null;

  for (var [studentClass, studentNames] of Object.entries(nameList)) {
    studentNames.forEach(([guestId,name]) => {
        const distance = levenshteinDistance(targetName.toLowerCase(), name.toLowerCase());
      if (distance < minDistance) {
        minDistance = distance;
        mostSimilar = {"name": name, "class": studentClass, "guestId": guestId};
      } 
    });
  }

  return mostSimilar;
}

function initialiseSelectors(studentClass){
    const classStudents = guestData[studentClass];
    const nameSelector = document.querySelector(".name-selector");
    nameSelector.innerHTML = "";
    classStudents.forEach(([guestId,name]) => {
        const option = document.createElement("option");
        option.value = guestId;
        option.innerText = name;
        nameSelector.appendChild(option);
    });
}
function initialiseUserDetails(username) {
    const similar = findMostSimilar(username, guestData);
    initialiseSelectors(similar.class);
}
initialiseUserDetails(username);

const classSelector = document.querySelector(".class-selector");
classSelector.addEventListener("change", (e) => {
    initialiseSelectors(e.target.value);
});

const submitButton = document.querySelector(".submit");
submitButton.addEventListener("click", () => {
  const nameSelector = document.querySelector(".name-selector");
  const guestId = nameSelector.value;
  console.log("Ran")
  postRequest("backend/Login/assignGuest.php", { guestId: guestId }, (result) => {
    console.log(result)
    if (result.success) {
      window.location.href = "index.php";
    } else {
      alert("Error: " + result.error)
    }
}, true, true)
})
