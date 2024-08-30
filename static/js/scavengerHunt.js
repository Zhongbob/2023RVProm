var resultContainer = document.getElementById('qr-reader-results');
var lastResult, countResults = 0;
const codeInput = document.getElementById("code");
const submitButton = document.getElementById("submit");
const website = "https://rvprom2023.x10.mx/index.php";
const codes = {
    "promrocks": 1,
    "rvprom2023": 2,
    "carpenoctem": 3,
    "starlight":4,
    "rulingthenight":5,
    "nightstars":6,
    "shine":7,
    "stars":8,
}
// remove trailing parameters
const url = website.split("?")[0];
console.log(url)
async function onScanSuccess(decodedText, decodedResult) {
    if (decodedText !== lastResult) {
        ++countResults;
        lastResult = decodedText;
        // Handle on success condition with the decoded message.

        console.log(decodedText.substring(0,url.length))
        if (decodedText.substring(0,url.length) !== url){ 
            alert("Invalid QR Code");
            return
        }
        window.location.href = decodedText;
        
    }
}

async function submitCode(){
    var code = document.getElementById("code").value.toLowerCase();
    console.log(code)
    if (codes[code] === undefined){
        alert("Invalid Code");
        return;
    }
    var response = await getRequest("backend/ScavengerHunt/getScavengerHuntCode.php",{
        scavengerHuntId:codes[code],
    });
    console.log(response)
    if (response.success){
        const urlCode = response.code;
        const url = `${website}?code=${urlCode}&scavengerHuntId=${codes[code]}`;
        window.location.href = url;
    }
}

submitButton.onclick = submitCode;

var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, aspectRatio:1.333334 });
html5QrcodeScanner.render(onScanSuccess);


