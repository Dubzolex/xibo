import { showStatus, api } from "/js/client.js"


const login = async () => {
    const req = {
        email: document.getElementById("input-email").value ?? null,
        password: document.getElementById("input-password").value ?? null
    }

    const res = await api("AUTH_LOGIN", req)
    showStatus(res)

    if(res.success) {
        localStorage.setItem("token", res.data.token)
        await new Promise(resolve => setTimeout(resolve, 1000))
        window.location.href = "./account/"
    }
}

document.getElementById("login").addEventListener("click", () => {
    login()
})
