
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


const api = async () => {
    const params = new URLSearchParams(window.location.search)
    const table = params.get("table")

    let req = {
        module: "control",
        action: table,
        data: {}
    }

    let res = await fetch(`../../api.php`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    document.getElementById("main").innerHTML = res.html

    document.getElementById("add-user").addEventListener("click", () => {
        addUser()
    })
}





const addUser = async () => {
    let req = {
        module: "control",
        action: "add",
        data: {
            email: document.getElementById("email").value ?? null,
            password: document.getElementById("password").value ?? null
        }
    }

    let res = await fetch(`../../api.php`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)
}

api()






























const read = async () => {
    let params = new URLSearchParams()
    params.append("module", "media")
    params.append("action", "s")
    
    
    

    console.log(response)
    showScreens(response.data)


    
    if(!screenId) {
        return
    }
    


    params = new URLSearchParams()
    params.append("module", "media")
    params.append("action", "get")
    

    const req = {
        "screenId": screenId
    }

    response = await fetch(`/src/api/api.php?${params.toString()}`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    }).then(res => res.json())

    if(response.html) {
        document.getElementById("content").innerHTML = response.html ?? null
        buttonAction() 
        showImages(response.data.images)
    
    }

    






}

const showScreens = async (screens) => {
    const div = document.getElementById("list-screen")

    if(div.length == 0) {
        div.innerHTML = `<em>No screen access</em>`
        return
    }

    const params = new URLSearchParams(window.location.search)
    screenId = params.get("s")

    div.innerHTML = screens.map(s => {
        return `
        <button id="${s.id}" class="link ${s.id == screenId ? "active" : ""}" >${s.name}</button>`
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
}



const showImages = async (images) => {
    const div = document.getElementById("list-images")

    if(images.length == 0) {
        div.innerHTML = `<em>No images found...</em>`
        return
    }

    div.innerHTML = images.map(m => {
        const url = `/images/${screenId}/${m}`
            
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


// ----------------------------
// UPLOAD D'IMAGES
// ----------------------------

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

    try {
        //Construction url
        const params = new URLSearchParams()
        params.append("action", "upload")
        params.append("id", screenId)

        const response = await fetch(`/backend/api/images.php?${params.toString()}`, {
            method: "POST",
            body: formData
        });

        const result = await response.text();

        await read()

    } catch (e) { 
        alert("Un problème est survenu !")
        console.error(e)
    }
}




// ----------------------------
// DELETE D'IMAGES
// ----------------------------

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
       
