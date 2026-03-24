import { isImage, isVideo } from "../frontend/utils.js"
import { verifySession } from "../frontend/utils.js"

verifySession()


const show = async (media) => {
    const div = document.getElementById("content")
    
    div.innerHTML = media.map((e, i) => {
        return `
        <div class="fx-col gap-100 jc-between h-400">
            <div class="fx-col gap-60">
                <div class="fx-col ai-center">
                    <div class="fx-row jc-between ai-center container w-1000 ai-center p-20">
                        <div class="fx-row gap-20 ai-center">
                            <h3>${e.label}</h3>
                        </div>
                        <div class="fx-row gap-10">
                            <div>online :</div>
                            <div class="${e.running == 1 ? "green" : "red"}">
                                <strong>${e.running == 1 ? "ON" : "OFF"}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="${e.id}" class="fx-row jc-evenly gap-80 wrap">${
                    e.images.map(m => {
                        const url = `../images/${e.id}/${m}`;

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

const api = async () => {
    let res = await fetch(`../api.php?action=MEDIA_SHOW`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })

    res = await res.json()

    console.log(res)

    await show(res.data)
}
api()