const api = async () => {
    let req = {
        token: localStorage.getItem("token")
    }

    let res = await fetch(`../../api.php?action=PROFIL_GET`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    res = await res.json()
    if(res.success) {
        show(res.data)
    } else {
        if(res.url) {
            window.location.href = res.url
        } else {
            localStorage.clear()
            window.location.href = "../"
        }
    }
}
api()



const show = async (u) => {
    const div = document.getElementById("content")
    if(div) {
        div.innerHTML = `
            <h3>Hello ${u.name}</h3>
            <div class="fx-col gap-10">
                <div class="fx-row jc-between">
                    <div>Name :</div>
                    <button class="action><a href="../credentials?edit=name">Edit</a><button>
                </div>
                <div class="fx-row jc-between">
                    <div>Email :</div>
                    <div>${u.email}</div>
                </div>
                <div class="fx-row jc-between">
                    <div>Password :</div>
                    <button class="action><a href="../credentials?edit=password">Edit</a><button>
                </div>
            <div>
            <div class="fx-row jc-center">
                <button class="action" id="logout">Logout</button>
            </div>
        
        `
    }
}