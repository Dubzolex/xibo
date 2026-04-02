import { api, showStatus } from "/js/client.js"
import { isImage, isVideo } from "../js/utils/media.js"

import { showMenu } from "/js/menu.js"

//showMenu()

const search = async () => {
    const div = document.getElementById("content") 

    div.innerHTML = await api("HTML_HOME", {
        token: localStorage.getItem("token")
    })
}
search()