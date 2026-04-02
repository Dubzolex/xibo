import { api, showStatus } from "/js/client.js"


const save = async () => {
    const container = document.getElementById("content");

    const req = {
        token: localStorage.getItem("token"),
        data: {}
    };
    container.querySelectorAll("input").forEach(input => {
        if (input.value) {
            req["data"][input.name] = input.value

        }
    });

    let res = await api("PROFIL_SAVE", req)
    showStatus(res)

    if(res.success) {
        await new Promise(resolve => setTimeout(resolve, 1000))
        window.location.href = "../account/"
    }
}


const search = async () => {
    const param = new URLSearchParams(window.location.search)

    let res = await api("PROFIL_EDIT", {
        type: param.get("edit")
    })

    showStatus(res)

    document.getElementById("content").innerHTML = res.html ?? null

    const btn = document.getElementById("save")
    if(btn) {
        btn.addEventListener("click", () => {
            save()
        })
    }
}
search()