import { api } from "/js/client.js"

export const showMenu = async () => {
    let res = await api("AUTH_SHOW",{
        token: localStorage.getItem("token")
    })

    const menu = document.getElementById("nav")
    if(menu) {
        menu.innerHTML = res.html ?? null
    }
}