import { isImage, isVideo } from "../frontend/utils.js"
import { verifySession } from "../frontend/utils.js"

verifySession()


let screenId = null

const showScreen = async (screens) => {
    const div = document.getElementById("list-screen")

    if(div.length == 0) {
        div.innerHTML = `<em>No screen access</em>`
        return
    }

    div.innerHTML = screens.map(s => {
        return `
        <button id="${s.id}" class="link ${s.id == screenId ? "active" : ""}" >${s.label}</button>`
    }).join("")

    screens.map(s => {
        document.getElementById(s.id).addEventListener("click", () => {
            if(s.id == screenId) {
                window.location.href = `./`

            } else {
                window.location.href = `./?s=${s.id}`
            } 
        })
    })

    if(screenId && !screens.map(e => e.id).includes(Number(screenId))) {
        window.location.href = "./"
    }
}



const showImages = async (images) => {
    const div = document.getElementById("list-images")

    if(!images || images.length == 0) {
        div.innerHTML = `
        <div class="fx-center">
            <em>No images found...</em>
        </div>`
        return
    }

    div.innerHTML = images.map(m => {
        const url = `../images/${screenId}/${m}`
            
            if (isImage(m)) {
                return `
                    <div class="fx-col ai-center gap-10">
                        <img src="${url}">
                        <div class="fx-row ai-center gap-10">
                            <input id ="${m}" type="checkbox">
                            <p>${m}</p>
                        </div>
                    </div>
                `
            } else if (isVideo(m)) {
                return `
                    <div class="fx-col ai-center gap-10">
                        <video autoplay muted playsinline>
                            <source src="${url}">
                        </video>
                        <div class="fx-row ai-center gap-10">
                            <input id ="${m}" type="checkbox">
                            <p>${m}</p>
                        </div>
                    </div>
                `
            }
            return null
        }).join("")
}


const uploadImage = async () => {
    const input = document.getElementById("file")

    if (!input.files.length) {
        alert("No image selected !")
        return;
    }

    // Ajouter chaque fichier
    const formData = new FormData();
    for (let file of input.files) {
        formData.append("file[]", file);
    }

    //Construction url
    const params = new URLSearchParams()
    params.append("action", "upload")
    params.append("id", screenId)

    const response = await fetch(`/backend/api/images.php?${params.toString()}`, {
        method: "POST",
        body: formData
    });

    const result = await response.text();

    await api()
}



const deleteImage = async () => {
    let mediaSelected = []

    for (let item of config.images) {
        const box = document.getElementById(item)

        if (box && box.checked) {
            mediaSelected.push(item)
        }
    }

    if(mediaSelected.length == 0){
        alert("No image selected !")
        return
    }

    const ok = confirm("Voulez-vous vraiment supprimer ces images ? " + mediaSelected.join(" / "))
    if(!ok){
        return
    }

    try {
        // Construction url
        const params = new URLSearchParams()
        params.append("action", "delete")
        params.append("id", screenId)
        
        mediaSelected.forEach(file => {
            params.append('file[]', file)
        })
        
        const response  = await fetch(`/backend/api/images.php?${params.toString()}`, {
            cache: 'no-store'
        })
        const result = await response.text()

        await read()
        
    } catch(e){
        alert("Un problème est survenu !")
        console.error(e)
    } 
}


const buttonAction = async () => {
    document.getElementById("delete").addEventListener("click", async (e) => {
        e.preventDefault()
        await deleteImage()
    })

    document.getElementById("upload").addEventListener("click", async (e) => {
        e.preventDefault();
        await uploadImage()
    })
}









































       
const api = async () => {
    // screens
    let req = {
        token: localStorage.getItem("token")
    }
        
    let res = await fetch(`../api.php?action=PROFIL_AUTHORIZE`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    const param = new URLSearchParams(window.location.search)
    screenId = param.get("s")

    await showScreen(res.data)

    if(!screenId) {
        console.log(screenId)
        return
    }

    console.log(screenId)

    // images
    req = {
        screenId: screenId
    }

    res = await fetch(`../api.php?action=MEDIA_GET`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()

    console.log(res)
    
    if(res.html) {
        document.getElementById("content").innerHTML = res.html ?? null
        buttonAction() 
        showImages(res.data)
    }
}
api()