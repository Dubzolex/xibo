<?php

header('Content-Type: application/json');

require __DIR__ . "/../backend/db.php";
require __DIR__ . "/../models/auth.php";
require __DIR__ . "/../models/media.php";
require __DIR__ . "/../models/profil.php";
require __DIR__ . "/../models/guard.php";
require __DIR__ . "/../models/control.php";

function api($module, $action, $data) {

    $db = new Database();

    switch ($module) {

        case "auth":
            $a = new Auth($db);
            return $a->action($action, $data);
        break;

        case "media":
            $m = new Media($db);
            return $m->action($action, $data);
        break;

        case "profil":
            $p = new Profil($db);
            return $p->action($action, $data);
        break;

        case "guard":
            $g = new Guard($db);
            return $g->action($action, $data);
        break;

        case "control":
            $a = new Control($db);
            return $a->action($action, $data);
        break;

        default:
            return [
                "success" => false,
                "message" => "Module non fourni.",
                "module" => $module,
                "action" => $action,
                "data" => $data
            ];
    }
}

$input = file_get_contents('php://input');
$json = json_decode($input, true);

$result = api($json['module'] ?? null, $json['action'] ?? null, $json['data'] ?? []);

echo json_encode($result);