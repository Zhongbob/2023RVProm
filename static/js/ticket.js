const qrcode = new QRCode("guest-code",{text:"hello"})

getRequest("backend/Tickets/getGuestCode.inc.php",{},function(response){
    console.log(response)
    if (response["success"]){
        qrcode.makeCode(response["code"])
    }
    else{
        alert(response["error"])
    }
})
