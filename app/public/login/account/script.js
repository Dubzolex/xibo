import { api, showStatus } from "/js/client.js"
import { showMenu } from "/js/menu.js"

showMenu()


const show = async (user) => {
    const div = document.getElementById("content")
    if(div) {
        div.innerHTML = `
            <h3>Hello ${user.name} !</h3>
            <div class="fx-row jc-between ai-center">
                <div>Name :</div>
                <a href="../credentials?edit=name"><button class="action">Edit</button></a>
            </div>
            <div class="fx-row jc-between">
                <div>Email :</div>
                <div>${user.email}</div>
            </div>
            <div class="fx-row jc-between ai-center">
                <div>Password :</div>
                <a href="../credentials?edit=password"><button class="action">Edit</button></a>
            </div>
            <div class="fx-row jc-between">
                <div>Role :</div>
                <div>${user.role}</div>
            </div>
            <div class="fx-row jc-between">
                <div>Last update :</div>
                <div>${user.updatedAt}</div>
            </div>
            <div class="fx-col gap-10">
                <div>Screen :</div>
                <div class="fx-row jc-center">
                    <ul class="fx-col jc-evenly gap-10 px-40">
                        ${user.screens.map(e => {
                            return `
                            <li>${e}</li>`
                        }).join("")}
                    </ul>
                </div>
            </div>
            
            <div class="fx-col ai-center gap-20">
                <div id="status"></div>
                <div class="fx-row jc-center">
                    <button class="action" id="logout">Logout</button>
                </div>
            </div>`

        document.getElementById("logout").addEventListener("click", () => {
            logout()
        })
    }
}


const logout = async () =>  {
    showStatus({ success: true, message: "Déconnexion..."})
    localStorage.removeItem("token")
    await new Promise(resolve => setTimeout(resolve, 2000))
    window.location.href = "../"
}




let res = await api("PROFIL_GET", {
    token: localStorage.getItem("token")
})

if(res.success) {
    show(res.data)
}

