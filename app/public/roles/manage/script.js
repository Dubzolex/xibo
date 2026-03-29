import { api } from "/js/client.js"
import { verifySession } from "/js/client.js"


verifySession()


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
                        <td ondblclick="makeEdit('user', this, ${u.id}, 'email')">${u.email ?? ""}</td>
                        <td ondblclick="makeEdit('user', this, ${u.id}, 'role')">${u.role ?? ""}</td>
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
                        <td ondblclick="makeEdit('screen', this, ${e.id}, 'label')">${e.label ?? ""}</td>
                        <td ondblclick="makeEdit('screen', this, ${e.id}, 'description')">${e.description ?? ""}</td>
                        <td ondblclick="makeEdit('screen', this, ${e.id}, 'format')">${e.format ?? ""}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.visible == 1 ? 'checked' : ''} 
                                    onchange="makeToggle('screen', ${e.id}, 'visible', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.running == 1 ? 'checked' : ''} 
                                    onchange="makeToggle('screen', ${e.id}, 'running', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.updating == 1 ? 'checked' : ''} 
                                    onchange="makeToggle('screen', ${e.id}, 'updating', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.controlled == 1 ? 'checked' : ''} 
                                    onchange="makeToggle('screen', ${e.id}, 'controlled', this.checked)">
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                `).join("")}
            </tbody>
        </table>`
}

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

    let res1 = await api("MANAGE_GET_USER")
    res1.json()
    let res2 = await api("MANAGE_GET_SCREEN")
    res2.json()

    showSelect(res1.data, res2.data)
}

const showSelect = async (users, screens) => {
    let div = document.getElementById("select-user")
    if(div) {
        div.innerHTML = `
        <option value="">User</option>
        ${users.sort((a,b) => a.email.localeCompare(b.email)).map(u => {
            return   `<option value="${u.id}">${u.email}</option>`
        }).join("")}`
    }

    div = document.getElementById("select-screen")
    if(div) {
        div.innerHTML = `
        <option value="">Screen</option>
        ${screens.sort((a,b) => a.label.localeCompare(b.label)).map(e => {
            return   `<option value="${e.id}">${e.label}</option>`
        }).join("")}`
    }
}


const addUser = async () => {
    await api("MANAGE_ADD_USER", {
        data: {
            email: document.getElementById("email").value ?? null,
        }
    })
    search()
}

const resetUser = async (u) => {
    if(confirm(`Réinitialiser le mot de passe de ${u.email} ?`)) {
        await api("MANAGE_RESET_USER", {
            id: u.id
        })
    }
    search()
}

const addScreen = async () => {
    await api("MANAGE_ADD_SCREEN", {
        data: {
            label: document.getElementById("name").value ?? null,
        }
    })
    search()
}

const addPermission = async (p) => {
    await api("MANAGE_ADD_PERMISSION", {
        data: {
            userId: document.getElementById("select-user").value,
            screenId: document.getElementById("select-screen").value,
        }
    })
    search()
}


const deletePermission = async (p) => {
    await api("MANAGE_DELETE_PERMISSION", {
        id: p.id
    })
    search()
}


const update = async (table, id, field, value) => {
    await api("MANAGE_UPDATE_" + table.toUpperCase(), {
        userId: id,
        screenId: id,
        data: {
            [field]: value
        }
    })
    search()
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


window.makeToggle= async (table, id, field, isChecked) => {
    const value = isChecked ? 1 : 0;
    let req = {
        screenId: id,
        data: {
            [field]: value
        }
    }

    await api("MANAGE_UPDATE_" + table.toUpperCase(), req)
}


const search = async () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")
    
    if(!table) {
        return
    }
    
    let res = await api("MANAGE_GET_" + table.toUpperCase())

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