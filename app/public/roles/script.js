import { api } from "/js/client.js"
import { showMenu } from "/js/menu.js"
import { showStatus } from "/js/client.js"

showMenu()


let screenId = null


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
        showStatus(data)

        search2()

    } catch (err) {
        console.error("Upload error:", err);
    }
};



const deleteImage = async () => {
    let get = await api("EDITOR_SHOW", {
        token: localStorage.getItem("token"),
        screenId: screenId
    })

    if(!get.success) return

    let mediaSelected = []

    for (let item of get.data) {
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

    let res = await api("EDITOR_DELETE", {
        screenId: screenId,
        images: mediaSelected
    })

    showStatus(res)

    search2()
}


const buttonLink = async (buttons) => {
    for(let s of buttons) {
        const btn = document.getElementById(s.id)

        if(btn) {
            if(s.id == screenId) {
                btn.classList.add("active")
            }

            btn.addEventListener("click", () => {
                if(s.id == screenId) {
                    window.location.href = `./`

                } else {
                    window.location.href = `./?s=${s.id}`
                } 
            })
        }
    }

    if(screenId && !buttons.map(e => e.id).includes(Number(screenId))) {
        window.location.href = "./"
    }
}


window.delete = () => {
    deleteImage()
}

window.upload = () => {
    uploadImage()
}




const search = async () => {
    const div = document.getElementById("sidebar")
    if(div) {
        let res = await api("SHOW_SIDEBAR", {
            token: localStorage.getItem("token"),
        })
        
        div.innerHTML = res.html

        const param = new URLSearchParams(window.location.search)
        screenId = Number(param.get("s"))
        await buttonLink(res.data)

        if(!screenId) return

        search2()
    }
}

const search2 = async () => {
    const div = document.getElementById("main")
    if(div) {
        div.innerHTML = await api("SHOW_MAIN", {
            token: localStorage.getItem("token"),
        })
        
        search3()
    }
}

const search3 = async () => {
    const param = new URLSearchParams(window.location.search)
    screenId = param.get("s")

    const div = document.getElementById("list")
    if(div) {
        div.innerHTML = await api("SHOW_LIST", {
            token: localStorage.getItem("token"),
        })
    }
}

search()