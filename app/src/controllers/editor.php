<?php

class Editor {

private $userModel;
private $screenModel;
private $mediaModel;


public function __construct($db) {
    $this->screenModel = new Screen($db);
    $this->mediaModel = new Media($db);
}

public function get($token) {
    try {
        $elements = $this->screenModel->getByToken($token);

        $results = array_map(function($item) {
            return [
                "id"    => $item["id"] ?? null,
                "label"  => $item["label"] ?? null,
                "running" => $item["is_running"] ?? null,
            ];
        }, $elements);

        return [
            "success" => true,
            "data" => $results
        ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage(),
        ];
    }
}

public function show($token, $screenId) {
    try {
        $elements = $this->screenModel->getByToken($token);
        if (!in_array($screenId, array_column($elements, 'id'))) {
            throw new Exception("Accès refusé.");
        }

    } catch (Exception $e) {
        return [
            "success" => false,
            "url" => "./",
            "error" => $e->getMessage()
        ];
    }

    try {
        $html = '
            <div class="fx-row w-600 jc-between ai-center gap-20 container px-20 py-10 mx-20">
                <div class="fx-row ai-center gap-10">
                    <input type="file" id="file" name="file[]" accept=".png, .jpg, .jpeg, .mp4" multiple>
                    <button id="upload" class="action bg-green">Upload</button>
                </div>
                <button id="delete" class="action bg-red">Delete</button>
            </div>';

        return [
            "success" => true,
            "data" =>  $this->mediaModel->get($screenId),
            "html" => $html
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function upload($screenId, $files) {
    try {
        return $this->mediaModel->upload($screenId, $files);

    } catch(Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function delete($screenId, $files) {
    try {
        return $this->mediaModel->delete($screenId, $files);

    } catch(Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

}

?>