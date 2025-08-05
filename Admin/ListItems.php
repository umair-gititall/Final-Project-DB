<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['folder'])) {
    http_response_code(400);
    echo json_encode(["error" => "Folder not specified"]);
    exit;
}

$relativeFolder = $_GET['folder'];

$targetDir = realpath(__DIR__ . '/' . $relativeFolder);

if (!$targetDir || !is_dir($targetDir)) {
    http_response_code(404);
    echo json_encode(["error" => "Invalid path"]);
    exit;
}

$projectRoot = realpath(__DIR__ . '/..');

$files = array_diff(scandir($targetDir), ['.', '..']);
$imagePaths = [];

foreach ($files as $file) {
    $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) {
        $relativeToRoot = str_replace($projectRoot, '', realpath($filePath));
        $relativeToRoot = str_replace(DIRECTORY_SEPARATOR, '/', $relativeToRoot);
        $imagePaths[] = '/LFMS' . $relativeToRoot;
    }
}

header('Content-Type: application/json');
echo json_encode($imagePaths);