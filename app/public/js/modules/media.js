export const isImage = (filename) => {
    const extensions = ['jpg', 'jpeg', 'png']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}

export const isVideo = (filename) => {
    const extensions = ['mp4']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}






const api = async (action, req = {}) => {
    let res = await fetch(`/api.php?action=${action}`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(req)
    })

    return await res.json()
}















/*
export API = {
    search(a)
}

/src
  /api
    - client.js      (Ton objet API centralisé)
  /modules
    - users.js       (Logique + HTML des utilisateurs)
    - screens.js     (Logique + HTML des écrans)
    - permissions.js (Logique + HTML des permissions)
  /utils
    - formatters.js  (Pour les dates, le texte, etc.)
    - validators.js  (Pour vérifier les emails)
  - main.js          (Le point d'entrée qui lie tout)


    const init = async () => {
    const params = new URLSearchParams(window.location.search);
    const table = params.get("table");
    
    if (routes[table]) {
        const res = await API.control(`GET_${table.toUpperCase()}`);
        document.getElementById("main").innerHTML = res.html; // Ton layout
        routes[table].render(res.data);
    }
};
*//*
window.addEventListener('refresh-data', init);
document.addEventListener("DOMContentLoaded", init);

// main.js
import { ScreenModule } from './modules/screens.js';
import { UserModule } from './modules/users.js';

const routes = {
    "screens": ScreenModule,
    "users": UserModule,
    // "permissions": PermissionModule
};


// modules/screens.js
import { API } from '../services/api.js';

export const ScreenModule = {
    async add() {
        const label = document.getElementById("name").value;
        await API.control("ADD_SCREEN", { data: { label } });
        window.dispatchEvent(new CustomEvent('refresh-data')); // Notifie le main de rafraîchir
    },

    async update(id, field, value) {
        return await API.control("UPDATE_SCREEN", { screenId: id, data: { [field]: value } });
    },

    render(data) {
        const div = document.getElementById("content-screen");
        if (!div) return;

        div.innerHTML = `
            <table>
                ${data.map(e => `
                    <tr>
                        <td ondblclick="makeEditable(this, ${e.id}, 'label')">${e.label}</td>
                        <td>
                            <input type="checkbox" ${e.visible == 1 ? 'checked' : ''} 
                                   onchange="ScreenModule.update(${e.id}, 'visible', this.checked ? 1 : 0)">
                        </td>
                    </tr>
                `).join('')}
            </table>`;
    }
};

// On l'attache à window si on utilise des onclick/onchange dans le HTML string
window.ScreenModule = ScreenModule;


// services/api.js
export const API = {
    control: async (action, data = {}) => {
        try {
            const res = await fetch(`../../api.php?action=MANAGE_${action}`, {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            return await res.json();
        } catch (error) {
            console.error(`Erreur API (${action}):`, error);
            return { status: "error", message: "Connexion perdue" };
        }
    }
};

// Pratique pour les listes générées
<button onclick="deleteUser(${u.id})">Supprimer</button>*/