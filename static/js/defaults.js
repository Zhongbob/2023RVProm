async function sleep(ms){
    return new Promise(resolve => setTimeout(resolve, ms));
}
function convertToFormData(data){
    let formObject = new FormData()
    for (const [key,value] of Object.entries(data)){
        formObject.append(key,value)
    }
    return formObject

}
async function postRequest(destination,data,action,secure,reportError){
    let formObject = new FormData()
    secure = secure ?? true
    data = data ?? {} 
    
    if (!(data instanceof FormData)){
        formObject = convertToFormData(data)
    }
    else{
        formObject = data
    }
    if (secure){
        var csrfMetaTag = document.querySelector('meta[name="csrf_token"]')
        if (!csrfMetaTag) { alert("Suspicious Change Detected. Please Reload Page."); return false}
        formObject.append('csrf_token',csrfMetaTag.getAttribute('content'))
    }
    let response = await fetch(destination, { method: "POST", body: formObject })
    let result
    try{
        result = await response.json()
    }
    catch (e){
        if (reportError){
            response = await fetch(destination, { method: "POST", body: formObject })
            result = await response.text()
            console.log(result)
            return result
        }
        console.error(e)
        return {success:false,error:`${e} Please contact rdevcca@gmail.com for assistance if this error persists.`}
    }
    return action ? action(result) : result 
    
}


async function getRequest(destination,data,action,secure,reportError){
    console.log("fetching")
    secure = secure ?? true;
    data = data ?? {} 
    destination += "?"
    if (secure){
        var csrfMetaTag = document.querySelector('meta[name="csrf_token"]')
        // if (!csrfMetaTag) { alert("Suspicious Change Detected. Please Reload Page.");return}
        destination += `csrf_token=${csrfMetaTag.getAttribute('content')}&`
    }
    for (const [key,value] of Object.entries(data)){
        destination += `${key}=${value}&`
    }
    // console.log(destination)
    response = await fetch(destination, 
        { method: "GET",
        headers: {
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        }})
        try{
            result = await response.json()
        }
        catch (e){
            console.log(destination)
            console.log(e)
            if (reportError){
                response = await fetch(destination, 
                    { method: "GET",
                    headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                    }})
                result = await response.text()
                console.log(result)
                return result
            }
            return ""
        }
    return action ? action(result) : result 
    
}

function createEle(eleTag,classList,id,attributes,styles){

    const ele = document.createElement(eleTag)
    classList = classList ?? []
    classList = typeof classList == "string" ? [classList]:classList 
    attributes = attributes ?? {}
    styles = styles ?? {}
    for (var className of classList){
        ele.classList.add(className)
    }
    for (var [attrName,attrVal] of Object.entries(attributes)){
        ele[attrName] = attrVal
    }
    for (var [styleName,styleVal] of Object.entries(styles)){
        ele.style[styleName] = styleVal
    }
    if (id) ele.id = id 
    return ele
}