export const dir = "/public";


const showMenu = () => {
    const nav = document.getElementById("nav")

    if(nav) {
        nav.innerHTML = `
            <a href="${dir}/home/"><h4>Home</h4></a>
            <a href="${dir}/roles/editor/"><h4>Editor</h4></a>
            <a href="${dir}/roles/admin/"><h4>Admin</h4></a>
            <a href="${dir}/login/"><h4>Login</h4></a>
            <a href="${dir}/login/account/"><h4>Profil</h4></a>
        `
    }
}

showMenu()