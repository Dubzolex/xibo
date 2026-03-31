import { api } from "/js/client.js"
import { isImage, isVideo } from "../js/utils/media.js"

import { showMenu } from "/js/menu.js"

showMenu()


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
    const input = document.getElementById("file");

    if (!input.files.length) {
        alert("No image selected!");
        return;
    }

    const formData = new FormData();

    // ajouter l'id
    formData.append("screenId", screenId);

    // ajouter les fichiers
    for (let file of input.files) {
        formData.append("file[]", file);
    }

    try {
        const res = await fetch(`/api.php?action=EDITOR_UPLOAD`, {
            method: "POST",
            body: formData
        });

        const data = await res.json();

        console.log("Response:", data);

        search2()

    } catch (err) {
        console.error("Upload error:", err);
    }
};



const deleteImage = async () => {
    let res = await api("EDITOR_SHOW", {
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

    await api("EDITOR_DELETE", {
        screenId: screenId,
        images: mediaSelected
    })

    search2()
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

    search2()
}

const search2 = async () => {
    // images
    let res = await api("EDITOR_SHOW", {
        token: localStorage.getItem("token"),
        screenId: screenId
    })
    
    if(res.html) {
        document.getElementById("content").innerHTML = res.html ?? null
        buttonAction() 
        showImages(res.data)
    }
}

search()