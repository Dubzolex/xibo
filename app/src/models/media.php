<?php

class Media {

private $db;
private $allowed = ['jpg', 'jpeg', 'png', 'mp4'];

private $dir = __DIR__ . "/../../public/images/";


public function __construct($database) {
    $this->db = $database;
}

public function get($screenId) {
    $path = $this->dir . $screenId . "/";

    if (!is_dir($path)) {
        return [];
    }

    return array_values(array_filter(
        scandir($path),
        function ($f) use ($path) {
            return is_file($path . '/' . $f) && preg_match('/\.(jpg|jpeg|png|mp4)$/i', $f);
        }
    ));

}

public function edit($screenId) {
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

        $files = $this->get($path);

        $html = '
            <div class="fx-row w-600 jc-between ai-center gap-20 container px-20 py-10 mx-20">
                <div class="fx-row ai-center gap-10">
                    <input 
                        type="file" 
                        id="file" 
                        name="file[]" 
                        accept=".png, .jpg, .jpeg, .mp4" 
                        multiple
                    >
                    <button id="upload" class="action bg-green">Upload</button>
                </div>
                <button id="delete" class="action bg-red">Delete</button>
            </div>';

        return [
            "success" => true,
            "data" =>  $files,
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
        $screens = $this->db->getScreens();

        $results = array_map(function($user) {
            return [
                "id"    => $user["id"] ?? null,
                "label"  => $user["label"] ?? null,
                "running" => $user["is_running"] ?? null,
                "images" => $this->get($user["id"]) ?? []
            ];
        }, $screens);

        return [
            "success" => true,
            "data" => $results
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
}

}

?>