<?php

class Viewer {

private $screenModel;
private $mediaModel;

public function __construct($db) {
    $this->screenModel = new Screen($db);
    $this->mediaModel = new Media($db);
}

public function get() {
    try {
        $elements = $this->screenModel->getAll();

        $results = array_map(function($item) {
            return [
                "id"    => $item["id"] ?? null,
                "label"  => $item["label"] ?? null,
                "running" => $item["is_running"] ?? null,
                "images" => $this->mediaModel->get($item["id"]) ?? [],
            ];
        }, $elements);

        return [
            "success" => true,
            "data" => $results
        ];

    } catch(Exception $e) {
        return [
            "success"=> false,
            "error"=> $e->getMessage(),
        ];
    }
}

}

?>