import { api } from "/js/client.js"
import { verifySession } from "../js/client.js"
import { isImage, isVideo } from "../js/utils/media.js"

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
    let images = []
    for (let file of input.files) {
        images.append(file)
    }
    
    let res = await api("EDITOR_UPLOAD", {
        screenId: screenId,
        images
    })
    
}



const deleteImage = async () => {
    let res = await api("EDITOR_GET", {
        token: localStorage.getItem("token"),
        screenId: screenId
    })

    if(!res.success) return

    let mediaSelected = []

    for (let item of res.data) {
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
        let res = await api("EDITOR_DELETE", {
            screenId: screenId,
            images: mediaSelected
        })
        
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

       
const search = async () => {
    // screens
    let res = await api("EDITOR_GET", {
        token: localStorage.getItem("token")
    })

    const param = new URLSearchParams(window.location.search)
    screenId = param.get("s")

    await showScreen(res.data)

    if(!screenId) return

    // images
    let res2 = await api("EDITOR_SHOW", {
        token: localStorage.getItem("token"),
        screenId: screenId
    })
    
    if(res2.html) {
        document.getElementById("content").innerHTML = res2.html ?? null
        buttonAction() 
        showImages(res2.data)
    }
}
search()