export const isImage = (filename) => {
    const extensions = ['jpg', 'jpeg', 'png']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}

export const isVideo = (filename) => {
    const extensions = ['mp4']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}

export const showStatus = async (data, element = "status") => {
    if(data?.url && data?.alert) {
        localStorage.clear()
        window.location.href = data.url
        return
    }    
    
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
        div.innerHTML = data.message

        if(data.success) {
            div.classList.add("green")
            
        } else {
            div.classList.add("red")
            console.warn(data.error)
        }

        await new Promise(resolve => setTimeout(resolve, 10000))
        div.innerHTML = ""
    }
}

export const verifySession = async () => {
    let req = {
        token: localStorage.getItem("token")
    }

    let res = await fetch(`/api.php?action=AUTH_VERIFY`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    showStatus(res)
}