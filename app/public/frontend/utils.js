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

export const status = async (data, element = "status") => {
    if(data?.alert) {
        alert(data.message)
        console.warn(data.error)
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
    const token = localStorage.getItem("token")
    if(!token) {
        window.location.href = "/login/"
    }
    const response = await fetch(`/backend/api/session.php?token=${token}`).then(r => r.json())
    console.warn(response)
    if(response == null) {
        window.location.href = "/login/"
    }
}