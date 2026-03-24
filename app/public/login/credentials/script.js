import { showStatus } from "../../frontend/utils.js"

const api = async () => {
    const param = new URLSearchParams(window.location.search)

    let req = {
        type: param.get("edit")
    }

    let res = await fetch(`../../api.php?action=PROFIL_EDIT`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    console.log(res)

    document.getElementById("content").innerHTML = res.html


    const btn = document.getElementById("save")
    if(btn) {
        btn.addEventListener("click", () => {
            save()
        })
    }
}
api()


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

    console.log(req)

    let res = await fetch(`../../api.php?action=PROFIL_SAVE`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()

    console.log(res)

    showStatus(res)
    if(res.success) {
        await new Promise(resolve => setTimeout(resolve, 2000))
        window.location.href = "../account/"
    }
}