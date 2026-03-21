import { isImage, isVideo } from "../frontend/utils.js"
import { verifySession } from "../frontend/utils.js"

verifySession()


const showScreen = async (data) => {
    const div = document.getElementById("list-screens")
    
    div.innerHTML = data.map((s, i) => {
        return `
        <div class="fx-col gap-100">
            <div class="fx-col gap-60">
                <div class="fx-col ai-center">
                    <div class="fx-row jc-between ai-center container w-1000 ai-center p-20">
                        <div class="fx-row gap-20 ai-center">
                            <div>#${s.id}</div>
                            <h3>${s.name}</h3>
                        </div>
                        <div class="fx-row gap-10">
                            <div>online :</div>
                            <div class="${s.actived == 1 ? "green" : "red"}">
                                <strong>${s.actived == 1 ? "ON" : "OFF"}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="${s.id}" class="fx-row jc-evenly gap-80 wrap">${
                    s.images.map(m => {
                        const url = `/images/${s.id}/${m}`;

                        if (isImage(m)) {
                            return `
                                <div class="fx-col ai-center gap-10">
                                    <img src="${url}">
                                    <div>${m}</div>
                                </div>
                            `
                        } else if (isVideo(m)) {
                            return `
                                <div class="fx-col ai-center gap-10">
                                    <video autoplay muted playsinline>
                                        <source src="${url}">
                                    </video>
                                    <div>${m}</div>
                                </div>
                            `
                        }
                        return null
                    }).join("")
                    }
                </div>
            </div>
            <div class="fx-col ai-center">
                <div class="fx-row w-1000">
                    <hr>
                </div>
            </div>
        </div>
        `
    }).join("")
}

const apiFetch = async () => {
    let req = {
        module: "media",
        action: "gets",
        data: {}
    }

    let res = await fetch(`../api.php`, {
        method: "POST",
        body: JSON.stringify(req)
    })
    res = await res.json()

    console.log(res)

    await showScreen(res?.data.screens)
}
apiFetch()