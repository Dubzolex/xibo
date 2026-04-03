import { showStatus } from "/js/client.js"
import { api } from "/js/client.js"
import { showMenu } from "/js/menu.js"

showMenu()


const addUser = async () => {
    await api("MANAGE_ADD_USER", {
        data: {
            email: document.getElementById("email").value ?? null,
        }
    })
    search2()
}

const resetUser = async (u) => {
    if(confirm(`Réinitialiser le mot de passe de ${u.email} ?`)) {
        let res = await api("MANAGE_RESET_USER", {
            id: u
        })
        showStatus(res)
    }
    search2()
}

window.resetU = (u) => {
    resetUser(u)
}

const addScreen = async () => {
    await api("MANAGE_ADD_SCREEN", {
        data: {
            label: document.getElementById("name").value ?? null,
        }
    })
    search2()
}

const addPermission = async () => {
    await api("MANAGE_ADD_PERMISSION", {
        data: {
            userId: Number(document.getElementById("select-user").value),
            screenId: Number(document.getElementById("select-screen").value),
        }
    })
    search2()
}

window.addP = () => {
    addPermission()
}


const deletePermission = async (p) => {
    await api("MANAGE_DELETE_PERMISSION", {
        id: p
    })
    search2()
}

window.deleteP = async (p) => {
    deletePermission(p)
}


const update = async (table, id, field, value) => {
    await api("MANAGE_UPDATE_" + table.toUpperCase(), {
        userId: id,
        screenId: id,
        data: {
            [field]: value
        }
    })
    search2()
}


window.makeEdit = (table, element, id, field) => {
    const oldValue = element.innerText
    const input = document.createElement("input")
    
    input.type = "text";
    input.value = oldValue
    input.style.width = "100%"; // S'adapte à la cellule
    
    element.innerHTML = ""
    element.appendChild(input)
    input.focus()

    // Sauvegarde les données
    const save = async () => {
        const newValue = input.value
        if (newValue !== oldValue) {
            await update(table, id, field, newValue)
        }
        element.innerText = newValue || ''
    };

    input.addEventListener("keydown", (e) => { if (e.key === "Enter") save() })
    input.addEventListener("blur", save); // Sauvegarde si on clique ailleurs
}


window.makeToggle= async (table, element, id, field) => {
    const newValue = element.checked ? 1 : 0;
    await update(table, id, field, newValue)
}


window.makeSelect = async (table, element, id, field) => {
    const newValue = element.value
    await update(table, id, field, newValue)
}


const button = () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")
    
    for(let b of ["user", "screen", "permission", "session"]) {
        const btn = document.getElementById(b)
        if(btn) {
            if(b == table) {
                btn.classList.add("active")
            }
                
            btn.addEventListener("click", () => {
                if(b == table) {
                     window.location.href = `./`

                } else {
                    window.location.href = `./?table=${b}`
                    button()
                }
            })
        }
    }
}



const search = async () => {
    const div = document.getElementById("content")
    if(div) {
        let res = await api("SHOW_ADMIN", {
            token: localStorage.getItem("token")
        })

        div.innerHTML = res.html ?? null
        button()

        search2()
    }
}



const search2 = async () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")
    
    if(!table) {
        return
    }
    console.log(table)

    const div = document.getElementById("main")
    if(div) {
        let res = await api("SHOW_TABLE", {
            token: localStorage.getItem("token"),
            table
        })

        div.innerHTML = res.html ?? null
    }
}

search()