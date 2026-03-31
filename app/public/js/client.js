export const api = async (action, req = {}) => {
    let res = await fetch(`/api.php?action=${action}`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()

    console.warn("")
    console.log("Request:", req)
    console.log("Action:", action)
    console.log("Response:", res)

    showStatus(res)
    return res
}

export const showStatus = async (data, element = "status") => {
    if(data?.url) {
        window.location.href = data.url
        return
    }

    if(data?.alert) {
        alert(data.alert)
        return
    }

    const div = document.getElementById(element)
    if(div) {
        div.classList.remove("green", "red")
        div.innerHTML = data.message ?? null

        if(data.success) {
            div.classList.add("green")
            
        } else {
            div.classList.add("red")
        }

        await new Promise(resolve => setTimeout(resolve, 10000))
        div.innerHTML = ""
    }
}

export const verifySession = async () => {
    let res = await api("AUTH_VERIFY", {
        token: localStorage.getItem("token")
    })

    if(!res.success) {
        window.location.href = "/login/"
    }
}