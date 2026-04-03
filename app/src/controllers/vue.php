<?php

class Vue {

private $userModel;
private $screenModel;
private $permissionModel;
private $sessionModel;
private $mediaModel;

public function __construct($db) {
    $this->userModel = new User($db);
    $this->screenModel = new Screen($db);
    $this->permissionModel = new Permission($db);
    $this->sessionModel = new Session($db);
    $this->mediaModel = new Media($db);
}

public function isImage($filename) {
    $extensions = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $extensions);
}

public function isVideo($filename) {
    $extensions = ['mp4'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $extensions);
}

public function content($res) {
    
    $elements = $this->screenModel->getAll();

    $data = [];
    foreach ($elements as $item) {
        $item['images'] = $this->mediaModel->get($item["id"]) ?? [];
        $data[] = $item;
    }

    //return $data;





    ob_start();
    // On inclut le fichier de template
    // Le template aura accès à $data et à $this (la classe Vue)
    include __DIR__ . '/../templates/main.content.php';
    return ob_get_clean();
}


public function renderContent() {
    $elements = $this->screenModel->getAll();
    $data = [];
    foreach ($elements as $item) {
        $item['images'] = $this->mediaModel->get($item["id"]) ?? [];
        $data[] = $item;
    }

    ob_start();
    include __DIR__ . '/../templates/main.content.php';
    return ob_get_clean();
}

public function renderSidebar($token) {
    $data = $this->screenModel->getByToken($token);

    ob_start();
    include __DIR__ . '/../templates/main.sidebar.php';
    return [
        "html" => ob_get_clean(),
        "data" => $data,
    ];
}

public function renderTool() {
    ob_start();
    include __DIR__ . '/../templates/main.tool.php';
    return [
        "html" => ob_get_clean(),
    ];
}

public function renderList($screenId) {
    $data = $this->mediaModel->get($screenId);

    ob_start();
    include __DIR__ . '/../templates/main.list.php';
    return [
        "html" => ob_get_clean(),
        "data" => $data,
    ];
}









public function renderAdmin($token) {


    $role = 2;

    ob_start();
    include __DIR__ . '/../templates/admin.content.php';
    return [
        "html" => ob_get_clean()
    ];
}


public function renderUser($role) {
    $data = $this->userModel->getAll();

    ob_start();
    include __DIR__ . '/../templates/admin.user.php';
    return [
        "html" => ob_get_clean(),
    ];
}

public function renderScreen($role) {
    $data = $this->screenModel->getAll();

    ob_start();
    include __DIR__ . '/../templates/admin.screen.php';
    return [
        "html" => ob_get_clean(),
    ];
}

public function renderPermission($role) {
    $data = $this->permissionModel->getAll();
    $users = $this->userModel->getAll();
    $screens = $this->screenModel->getAll();

    ob_start();
    include __DIR__ . '/../templates/admin.permission.php';
    return [
        "html" => ob_get_clean(),
    ];
}

public function renderSession($role) {
    $data = $this->sessionModel->getAll();

    ob_start();
    include __DIR__ . '/../templates/admin.session.php';
    return [
        "html" => ob_get_clean(),
    ];
}

























































/*


    $html = '';

    foreach ($data as $e) {
        // On prépare les éléments dynamiques
        $statusClass = ($e['is_running'] == 1) ? "green" : "red";
        $statusText  = ($e['is_running'] == 1) ? "ON" : "OFF";
        
        // Gestion des médias (images/vidéos)
        $mediaHtml = '';
        foreach ($e['images'] as $m) {
            $url = "../images/{$e['id']}/$m";
            
            if ($this->isImage($m)) {
                $mediaHtml .= "
                    <div class=\"fx-col ai-center gap-10\">
                        <img src=\"$url\">
                        <div>$m</div>
                    </div>";
            } elseif ($this->isVideo($m)) {
                $mediaHtml .= "
                    <div class=\"fx-col ai-center gap-10\">
                        <video autoplay muted playsinline>
                            <source src=\"$url\">
                        </video>
                        <div>$m</div>
                    </div>";
            }
        }

        // Assemblage du template
        if($e["is_visible"] == 1) {
            $html .= <<<HTML
            <div class="fx-col gap-100 jc-between h-400">
                <div class="fx-col gap-60">
                    <div class="fx-col ai-center">
                        <div class="fx-row jc-between ai-center container w-1000 ai-center p-20">
                            <div class="fx-row gap-20 ai-center">
                                <h3>{$e['label']}</h3>
                            </div>
                            <div class="fx-row gap-10">
                                <div>online :</div>
                                <div class="$statusClass">
                                    <strong>$statusText</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="{$e['id']}" class="fx-row jc-evenly gap-80 wrap">
                        $mediaHtml
                    </div>
                </div>
                <div class="fx-col ai-center">
                    <div class="fx-row w-1000">
                        <hr>
                    </div>
                </div>
            </div>HTML;
        }
    }

    return $html;
}*/

}

?>