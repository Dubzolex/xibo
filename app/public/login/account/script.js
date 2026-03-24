import { showStatus } from "../../frontend/utils.js"
import { verifySession } from "../../frontend/utils.js"

verifySession()


const show = async (u) => {
    const div = document.getElementById("content")
    if(div) {
        div.innerHTML = `
            <h3>Hello ${u.name} !</h3>
            <div class="fx-row jc-between ai-center">
                <div>Name :</div>
                <a href="../credentials?edit=name"><button class="action">Edit</button></a>
            </div>
            <div class="fx-row jc-between">
                <div>Email :</div>
                <div>${u.email}</div>
            </div>
            <div class="fx-row jc-between ai-center">
                <div>Password :</div>
                <a href="../credentials?edit=password"><button class="action">Edit</button></a>
            </div>
            <div class="fx-row jc-between">
                <div>Role :</div>
                <div>${u.role}</div>
            </div>
            <div class="fx-row jc-between">
                <div>Last update :</div>
                <div>${u.updatedAt}</div>
            </div>
            <div class="fx-col gap-10">
                <div>Screen :</div>
                <div class="fx-row jc-center">
                    <ul class="fx-col jc-evenly gap-10 px-40">
                        ${u.screens.map(e => {
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



const api = async () => {
    let req = {
        token: localStorage.getItem("token")
    }

    let res = await fetch(`../../api.php?action=PROFIL_GET`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    showStatus(res)

    if(res.success) {
        show(res.data)
    }
}
api()