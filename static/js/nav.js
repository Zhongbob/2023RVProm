const titleTag = document.querySelector('meta[name="pageTitle"]')
const pageTitle = titleTag?titleTag.getAttribute('content'):null
if (pageTitle){
    const navItem = document.getElementById(pageTitle)
    if (navItem){
        navItem.classList.add("active")
    }
}