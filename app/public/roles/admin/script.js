const button = () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")
    
    for(let b of ["users", "screens", "permissions", "sessions"]) {
        const div = document.getElementById(b)
        if(div) {
            if(b == table) {
                div.classList.add("active")
            }
                
            div.addEventListener("click", () => {
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






/* screens */

const addUser = async () => {
    let req = {
        email: document.getElementById("email").value ?? null,
        password: document.getElementById("password").value ?? null
    }

    let res = await fetch(`../../api.php?action=CONTROL_ADD_USER`, {
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


const showUser = async (users) => {
    const div = document.getElementById("content-user")

    if(div) {
        document.getElementById("add-user").addEventListener("click", () => {
            addUser()
        })

        div.innerHTML = users.sort((a, b) => a.email.localeCompare(b.email)).map(u => {
            return `
            <div class="fx-row p-20 container jc-between ai-center">
                <div class="">${u.email}</div>
                <button class="action" id="reset-${u.id}">Reset</button>
            </div>
            `
        }).join("")
    }
}


/* screens */

const addScreen = async () => {
    let req = {
        name: document.getElementById("name").value ?? null,
    }

    let res = await fetch(`../../api.php?action=CONTROL_ADD_SCREEN`, {
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


const showScreen = async (screens) => {
    const div = document.getElementById("content-screen")

    if(div) {
        document.getElementById("add-screen").addEventListener("click", () => {
            addScreen()
        })

        div.innerHTML = screens.map(u => {
            return `
            <div class="fx-row p-20 container jc-between ai-center">
                <div class="">${u.name}</div>
                <div>${u.running}</div>
                <div>${u.updating}</div>
                <div>${u.controlled}</div>
            </div>
            `
        }).join("")
    }
}


























































const api = async () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")

    let maps = {
        "screens": "SCREEN",
        "users": "USER",
        "permissions": "PERMISSION",
        "sessions": "SESSION"
    }

    let req = {}

    console.log(maps[table])
        
    let res = await fetch(`../../api.php?action=CONTROL_GET_${maps[table]}`, {
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
    }

}
api()