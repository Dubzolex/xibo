<?php
header('Content-Type: application/json');

// Récupérer le nom de l'écran depuis le paramètre GET
$screen = isset($_GET['dir']) ? basename($_GET['dir']) : null;

if (!$screen) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètre dir manquant']);
    exit;
}

// Chemin vers le dossier Ecran dans ton montage Docker/NAS
$baseDir = __DIR__ . '/../images'; // correspond à /var/www/html/images si monté
$dir = $baseDir . '/' . $screen;

// Sécurité : vérifier que le dossier existe
if (!is_dir($dir)) {
    http_response_code(404);
    echo json_encode(['error' => "Dossier '$screen' introuvable",
        "base" => $baseDir,
        "folder"=> $dir]);
    exit;
}

// Lister les fichiers images et vidéos
$files = array_values(array_filter(scandir($dir), function ($f) use ($dir) {
    return is_file($dir . '/' . $f)
        && preg_match('/\.(jpg|jpeg|png|mp4|webm)$/i', $f); // ajout vidéos
}));

$result = [
    "images" => $files
];

echo json_encode($result);
