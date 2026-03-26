import { api } from "/js/client.js"
import { verifySession } from "/js/client.js"


verifySession()


const button = () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")
    
    for(let b of ["users", "screens", "permissions", "sessions"]) {
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
button()


const showUser = async (users) => {
    const div = document.getElementById("content-user")
    if(!div) return

    document.getElementById("add-user").addEventListener("click", () => {
        addUser()
    })

    div.innerHTML = `
        <table>
            <thead>
                <th style="width: 15%;">Name</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 12%;">Role</th>
                <th style="width: 20%;">Updated</th>
                <th style="width: 12%;">Password</th>
                <th style="width: 20%;">Changed</th>
            </thead>
            <tbody class="scroll-y">
                ${users.sort((a, b) => a.email.localeCompare(b.email)).map(u => `
                    <tr>
                        <td>${u.name ?? "-"}</td>
                        <td ondblclick="makeEdit('USER', this, ${u.id}, 'email')">${u.email ?? ""}</td>
                        <td>${u.role}</td>
                        <td>${u.updatedAt ?? "-"}</td>
                        <td>
                            <button class="action bg-red" id="reset-${u.id}">Reset</button>
                        </td>
                        <td>${u.changedAt ?? "-"}</td>
                    </tr>
                `).join("")}
            </tbody>
        </table>`

    users.map(u => {
        const btn = document.getElementById(`reset-${u.id}`)
        if(btn) {
            btn.addEventListener("click", () => {
                resetUser(u)
            })
        }
    })
}


const showScreen = async (screens) => {
    const div = document.getElementById("content-screen")
    if(!div) return 

    document.getElementById("add-screen").addEventListener("click", () => {
        addScreen()
    })

    div.innerHTML = `
        <table>
            <thead>
                <th style="width: 20%;">Label</th>
                <th style="width: 20%;">Description</th>
                <th style="width: 20%;">Format</th>
                <th style="width: 10%;">Visible</th>
                <th style="width: 10%;">Running</th>
                <th style="width: 10%;">Updating</th>
                <th style="width: 10%;">Controlled</th>
            </thead>
            <tbody class="scroll-y">
                ${screens.map(e => `
                    <tr>
                        <td ondblclick="makeEditable(this, ${e.id}, 'label')">${e.label ?? ""}</td>
                        <td ondblclick="makeEditable(this, ${e.id}, 'description')">${e.description ?? ""}</td>
                        <td ondblclick="makeEditable(this, ${e.id}, 'format')">${e.format ?? ""}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.visible == 1 ? 'checked' : ''} 
                                    onchange="toggleScreen(${e.id}, 'visible', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.running == 1 ? 'checked' : ''} 
                                    onchange="toggleScreen(${e.id}, 'is_running', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.updating == 1 ? 'checked' : ''} 
                                    onchange="toggleScreen(${e.id}, 'is_updating', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.controlled == 1 ? 'checked' : ''} 
                                    onchange="toggleScreen(${e.id}, 'is_controlled', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                `).join("")}
            </tbody>
        </table>`
}



















































































































































































































/* screens */

const addUser = async () => {
    let req = {
        data: {
            email: document.getElementById("email").value ?? null,
        }
    }

    let res = await fetch(`../../api.php?action=MANAGE_ADD_USER`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    showStatus(res)

    api()
}




const resetUser = async (u) => {
    let choix = confirm(`Réinitialiser le mot de passe de ${u.email} ?`)
    
    if(choix) {
        let req = {
            id: u.id
        }

        let res = await fetch(`../../api.php?action=MANAGE_RESET_USER`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(req)
        })

        res = await res.json()
        console.log(res)

        showStatus(res)

        api()
    }
}

window.makeEdit = (table, element, id, field) => {
    const oldValue = element.innerText
    const input = document.createElement("input")
    
    input.type = "text";
    input.value = oldValue === '---' ? '' : oldValue
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
        element.innerText = newValue || '---'
    };

    input.addEventListener("keydown", (e) => { if (e.key === "Enter") save() })
    input.addEventListener("blur", save); // Sauvegarde si on clique ailleurs
}


const update = async (table, id, field, value) => {
    await api("MANAGE_UPDATE_" + table, {
        userId: id,
        data: {
            [field]: value
        }
    })
}


























/* screens */

const addScreen = async () => {
    let req = {
        data: {
            label: document.getElementById("name").value ?? null,
        }
    }

    let res = await fetch(`../../api.php?action=MANAGE_ADD_SCREEN`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()

    api()
}

window.toggleScreen = async (id, field, isChecked) => {
    const value = isChecked ? 1 : 0;

    let res = await fetch(`../../api.php?action=MANAGE_UPDATE_SCREEN`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            screenId: id,
            data: {
                [field]: value
            }
        })
    });

    res = await res.json();
    
    console.log(res)

}

window.makeEditable = (element, id, field) => {
    const oldValue = element.innerText
    const input = document.createElement("input")
    
    input.type = "text";
    input.value = oldValue === '---' ? '' : oldValue
    input.style.width = "100%"; // S'adapte à la cellule
    
    element.innerHTML = ""
    element.appendChild(input)
    input.focus()

    // Sauvegarde les données
    const save = async () => {
        const newValue = input.value
        if (newValue !== oldValue) {
            await updateScreen(id, field, newValue)
        }
        element.innerText = newValue || '---'
    };

    input.addEventListener("keydown", (e) => { if (e.key === "Enter") save() })
    input.addEventListener("blur", save); // Sauvegarde si on clique ailleurs
}


const updateScreen = async (id, field, value) => {
    let req = {
        screenId: id,
        data: {
            [field]: value
        }
    }

    let res = await fetch(`../../api.php?action=MANAGE_UPDATE_SCREEN`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })
    res = await res.json()
    api()
}


















/* session */



const showPermission = async (permissions) => {
    const div = document.getElementById("content-permission")

    if(!div) return
    div.innerHTML = `
        <table>
            <thead>
                <th style="width: 40%;">User</th>
                <th style="width: 40%;">Screen</th>
                <th style="width: 20%;">Manage</th>
            </thead>
            <tbody class="scroll-y">
                ${permissions.sort((a, b) => a.email.localeCompare(b.email))
                    .map(p => `
                        <tr>
                            <td>${p.email}</td>
                            <td>${p.label}</td>
                            <td>
                                <button class="action bg-red" id="delete-${p.id}">Delete</button>
                            </td>
                        </tr>
                    `).join("")}
            </tbody>
        </table>`

    permissions.map(p => {
        const btn = document.getElementById(`delete-${p.id}`)
        if(btn) {
            btn.addEventListener("click", () => {
                deletePermission(p)
            })
        }
    })

    document.getElementById("add-permission").addEventListener("click", () => {
        addPermission()
    })

    let res1 = await fetch(`../../api.php?action=MANAGE_GET_USER`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    res1 = await res1.json()

    let res2 = await fetch(`../../api.php?action=MANAGE_GET_SCREEN`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    res2 = await res2.json()
    
    document.getElementById("select-user").innerHTML = `
        <option value="">User</option>
        ${res1.data.sort((a,b) => a.email.localeCompare(b.email)).map(u => {
            return   `<option value="${u.id}">${u.email}</option>`
        }).join("")}`
    
    document.getElementById("select-screen").innerHTML = `
        <option value="">Screen</option>
        ${res2.data.sort((a,b) => a.label.localeCompare(b.label)).map(e => {
            return   `<option value="${e.id}">${e.label}</option>`
        }).join("")}`
}

const addPermission = async (p) => {
    let req = {
        data: {
            userId: document.getElementById("select-user").value,
            screenId: document.getElementById("select-screen").value,
        }
    }

    let res = await fetch(`../../api.php?action=MANAGE_ADD_PERMISSION`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()

    api()
}


const deletePermission = async (p) => {
    let req = {
        id: p.id
    }

    let res = await fetch(`../../api.php?action=MANAGE_DELETE_PERMISSION`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    api()
}




















/* session */

const showSession = async (sessions) => {
    const div = document.getElementById("content-session")
    if(!div) return
    
    div.innerHTML = `
        <table>
            <thead>
                <th style="width: 25%;">Name</th>
                <th style="width: 30%;">Email</th>
                <th style="width: 20%;">Connected</th>
                <th style="width: 20%;">Expires</th>
                <th style="width: 10%;">Token</th>
            </thead>
            <tbody class="scroll-y">
                ${sessions
                    .sort((a, b) => b.connectedAt.localeCompare(a.connectedAt))
                    .map(s => {return `
                        <tr>
                            <td>${s.name ?? "-"}</td>
                            <td>${s.email}</td>
                            <td>${s.connectedAt}</td>
                            <td>${s.expiresAt}</td>
                            <td class="scroll-x" style="max-width: 200px;">${s.token}</td>
                        </tr>
                    `}).join("")}
            </tbody>
        </table>`
    
}


const search = async () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")

    let maps = {
        "screens": "SCREEN",
        "users": "USER",
        "permissions": "PERMISSION",
        "sessions": "SESSION"
    }
    
    if(!table) {
        return
    }

    //console.log(maps[table])

    let req = {}
        
    let res = await fetch(`../../api.php?action=MANAGE_GET_${maps[table]}`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    const div = document.getElementById("main")

    if(div && res.html) {
        div.innerHTML = res.html
        showUser(res.data)
        showScreen(res.data)
        showSession(res.data)
        showPermission(res.data)
    }

}
search()