export const showMenu = async (html) => {
    const menu = document.getElementById("nav")
    if(menu) {
        menu.innerHTML = html
    }
}