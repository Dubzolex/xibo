<?php

ob_start();

header('Content-Type: application/json');


require_once __DIR__ . "/../config.php";


$db = Database::getInstance();

$auth = new Auth($db);
$profil = new Profil($db);
$viewer = new Viewer($db);
$editor = new Editor($db);
$manager = new Manager($db);

$routes = [
    /* Controller Auth */
    "AUTH_LOGIN" => function($req) use ($auth) {
        return $auth->connect($req["email"]  ?? $_GET["email"] ?? null, $req["password"]  ?? $_GET["password"]?? null);
    },

    "AUTH_LOGOUT" => function($req) use ($auth) {
        return $auth->disconnect();
    },

    "AUTH_VERIFY" => function($req) use ($auth) {
        return $auth->verify($req["token"] ?? $_GET["token"] ?? null);
    },

    "AUTH_SHOW" => function($req) use ($auth) {
        return $auth->authorize($req["token"] ?? $_GET["token"] ?? null);
    },



    /* Controller Profil */
    "PROFIL_GET" => function($req) use ($profil) {
        return $profil->get($req["token"] ?? $_GET["token"] ?? null);
    },

    "PROFIL_EDIT" => function($req) use ($profil) {
        return $profil->edit($req["type"] ?? $_GET["type"] ?? null);
    },

    "PROFIL_SAVE" => function($req) use ($profil) {
        return $profil->save($req["token"] ?? $_GET["token"] ?? null, $req["data"]);
    },


    /* Controller Viewer */
    "VIEWER_SHOW" => function($req) use ($viewer) {
        return $viewer->get();
    },


    /* Controller Editor */
    "EDITOR_GET" => function($req) use ($editor) {
        return $editor->get($req["token"] ?? $_GET["token"] ?? null);
    },

    "EDITOR_SHOW" => function($req) use ($editor) {
        return $editor->show($req["token"] ?? $_GET["token"] ?? null, $req["screenId"] ?? $req["id"] ?? $_GET["id"] ?? null);
    },

    "EDITOR_UPLOAD" => function() use ($editor) {
        
        return $editor->upload($_POST["screenId"] ?? $_GET["id"] ?? null, $_FILES);
},

    "EDITOR_DELETE" => function($req) use ($editor) {
        return $editor->delete($req["screenId"] ?? $req["id"] ?? $_GET["id"] ?? null, $req["images"] ?? []);
    },


    /* Control Users */

    "MANAGE_GET_USER" => function($req) use ($manager) {
        return $manager->getUsers();
    },

    "MANAGE_ADD_USER" => function($req) use ($manager) {
        return $manager->addUser($req["data"]);
    },

    "MANAGE_UPDATE_USER" => function($req) use ($manager) {
        return $manager->updateUser($req["userId"] ?? $req["id"], $req["data"]);
    },

    "MANAGE_RESET_USER" => function($req) use ($manager) {
        return $manager->resetUser($req["userId"] ?? $req["id"] ?? $_GET["id"]);
    },

    /* Control Screens */

    "MANAGE_GET_SCREEN" => function($req) use ($manager) {
        return $manager->getScreens();
    },

    "MANAGE_ADD_SCREEN" => function($req) use ($manager) {
        return $manager->addScreen($req["data"]);
    },

    "MANAGE_UPDATE_SCREEN" => function($req) use ($manager) {
        return $manager->updateScreen($req["screenId"] ?? $req["id"], $req["data"]);
    },

    /* Control Permissions */

    "MANAGE_GET_PERMISSION" => function($req) use ($manager) {
        return $manager->getPermissions();
    },

    "MANAGE_ADD_PERMISSION" => function($req) use ($manager) {
        return $manager->addPermission($req["data"]);
    },

    "MANAGE_DELETE_PERMISSION" => function($req) use ($manager) {
        return $manager->deletePermission($req["permissionId"] ?? $req["id"]);
    },

    /* Control Permissions */

    "MANAGE_GET_SESSION" => function($req) use ($manager) {
        return $manager->getSessions();
    }
];

function api($routes) {
    $action = $_GET["action"] ?? null;

    if(!$action) {
        return [
            "success" => false,
            "message" => "Action non fournie.",
            "action" => $action
        ];
    }

    if(isset($routes[$action])) {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);

        return $routes[$action]($json);
    }

    return [
        "success" => false,
        "message" => "Action non identifée.",
        "action" => $action
    ];
    
}

$result = api($routes);
ob_end_clean();
echo json_encode($result);