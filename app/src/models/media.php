<?php

class Media {

private $db;
private $allowed = ['jpg', 'jpeg', 'png', 'mp4'];

private $dir = __DIR__ . "/../../images/";


public function __construct($database) {
    $this->db = $database;
}

public function getById($screenId) {
    try {
        $path = $this->dir . $screenId . "/";

        if (!is_dir($path)) {
            return [
                "success" => false,
                "message" => "Dossier introuvable",
                "data" => [
                    "folder" => $path
                ]
            ];
        }

        $files = array_values(array_filter(
            scandir($path),
            function ($f) use ($path) {
                return is_file($path . '/' . $f) && preg_match('/\.(jpg|jpeg|png|mp4)$/i', $f);
            }
        ));

        $html = '
            <div class="fx-row w-800 jc-evenly ai-center gap-20 container m-20 px-20 py-10">
                <div class="fx-row gap-20 ai-center gap-10">
                    <input 
                        type="file" 
                        id="file" 
                        name="file[]" 
                        accept=".png, .jpg, .jpeg, .mp4" 
                        multiple
                    >
                    <button id="upload" class="action">Upload</button>
                </div>
                <button id="delete" class="action">Delete</button>
            </div>';

        return [
            "success" => true,
            "data" => [
                "images" => $files
            ],
            "html" => $html
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la récupération des fichiers.",
            "error" => $e->getMessage()
        ];
    }
}

public function upload($screenId, $files) {
    try {
        $path = $this->dir . $screenId . "/";

        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }

        // Extensions autorisées
        $allowed = ['png', 'jpg', 'jpeg', 'mp4'];
        $uploadedFiles = [];

        foreach ($files['file']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($files['file']['name'][$key]);
            $fileError = $files['file']['error'][$key];

            if ($fileError !== UPLOAD_ERR_OK) {
                return [
                    "success" => false,
                    "message" => "Fichier corrompu: " . $fileName,
                    "data" => [
                        "file" => $fileName
                    ]
                ];
            }

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed)) {
                return [
                    "success" => false,
                    "message" => "Extension non autorisée: " . $fileName,
                    "data" => [
                        "file" => $fileName
                    ]
                ];
            }

            $destination = $path . $fileName;

            if (!move_uploaded_file($tmpName, $destination)) {
                return [
                    "success" => false,
                    "message" => "Image perdu: " . $fileName,
                    "data" => [
                        "file" => $fileName
                    ]
                ];
            }

            $uploadedFiles[] = $fileName;
        }

        return [
            "success" => true,
            "message" => "Images chargées avec succès.",
            "data" => [
                "files" => $uploadedFiles
            ]
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors du chargement des fichiers.",
            "error" => $e->getMessage()
        ];
    }
}

public function delete($screenId, $files) {
    try {
        $path = $this->dir . $screenId . "/";

        if (!is_dir($path)) {
            return [
                "success" => false,
                "message" => "Dossier introuvable",
                "data" => [
                    "folder" => $path
                ]
            ];
        }

        foreach ((array)$files as $file) {
            $fileName = basename($file);
            $filePath = $path . $fileName;

            if (file_exists($filePath)) {
                unlink($filePath);

            } else {
                return [
                    "success" => false,
                    "message" => "Fichier introuvable: " . $fileName,
                    "data" => [
                        "folder" => $path,
                        "file" => $fileName
                    ]
                ];
            }
        }

        return [
            "success" => true,
            "message" => "Supression terminée.",
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la supression.",
            "error" => $e->getMessage()
        ];
    }
}

public function getAll() {
    try {
        $results = $this->db->getScreens();

        if(!$results["success"]) {
            return $results;
        }

        foreach ($results["data"] as $r) {
            //echo json_encode($r);
            $s = [
                "id" => $r["id"] ?? null,
                "name" => $r["name"] ?? null,
                "online" => $r["actived"] ?? null,
                "controlled" => $r["managed"] ?? null
            ];

            $img = $this->get($r["id"]);
            if(isset($img['data'])) {
                $d = $img['data'];
                if(isset($d['images'])) {
                    $s["images"] = $d['images'];
                } else {
                    $s["images"] = [];
                }

            } else {
                $s["images"] = [];
            }
            $screens[] = $s; 
        }

        return [
            "success" => true,
            "data" => [
                "screens" => $screens
            ]
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la supression.",
            "error" => $e->getMessage()
        ];
    }
}

public function edit($token = null) {
    return $this->db->getScreens();
    return $this->db->getScreensBySessionToken($token);
}

public function action($action, $params) {

    switch($action) {
        case "get":
            return $this->get($params["screenId"]);
            break;

        case "upload":
            return $this->upload($params["screenId"], $_FILES);
            break;

        case "delete":
            return $this->delete($params["screenId"], $params["files"]);
            break;

        case "gets":
            return $this->getAll();
            break;

        case "allow":
            return $this->db->getScreens();
            break;

        default:
            return [
                "success" => false,
                "message" => "Action media non précisée.",
                "action" => $action
            ];
    }

}

}

?>