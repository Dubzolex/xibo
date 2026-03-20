import { status } from "../frontend/utils.js"


const login = async () => {
    let req = {
        email: document.getElementById("input-email").value ?? null,
        password: document.getElementById("input-password").value ?? null
    }

    let res = await fetch(`../api.php?action=AUTH_LOGIN`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    status(res)

    if(res.success) {
        localStorage.setItem("token", res.data.token)
        window.location.href = "./account/"
    }
}

document.getElementById("login").addEventListener("click", () => {
    login()
})
