const popupLoadingDiv = document.querySelector(".popup-loading"),
  errorMsgEle = document.querySelector(".loading-error"),
  checkEle = document.querySelector(".check-label");

function onLoading() {
  popupLoadingDiv.style.display = "flex";
  checkEle.dataset.status = "Loading";
  errorMsgEle.innerText = "";
}

function onErrorMessage(message) {
  // Handles errors I assume
  fileInput.disabled = false;
  checkEle.dataset.status = "Failure";
  errorMsgEle.innerText = message;
  popupLoadingDiv.onclick = function () {
    this.style.display = "none";
    checkEle.dataset.status = "";
    this.onclick = "";
  };
}

function onSuccess(message) {
  if (message) {
    checkEle.dataset.status = "Success";
    errorMsgEle.innerText = message;
    popupLoadingDiv.onclick = function () {
      this.style.display = "none";
      checkEle.dataset.status = "";
      this.onclick = "";
    };
    return
  }
  checkEle.dataset.status = "Success";
  setTimeout(() => {
    checkEle.dataset.status = "";
    popupLoadingDiv.style.display = "none";
  }, 500);
}
