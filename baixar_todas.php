<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Busca todas as imagens do usuário
$stmt = $conn->prepare("SELECT caminho FROM imagens WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$zipNome = "imagens_usuario_$usuario_id.zip";
$zipCaminho = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipNome;

$zip = new ZipArchive;
if ($zip->open($zipCaminho, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    while ($row = $result->fetch_assoc()) {
        $caminhoRelativo = $row['caminho'];
        if (file_exists($caminhoRelativo)) {
            $nomeNoZip = basename($caminhoRelativo);
            $zip->addFile($caminhoRelativo, $nomeNoZip);
        }
    }
    $zip->close();

    // Força o download
    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=\"$zipNome\"");
    header('Content-Length: ' . filesize($zipCaminho));
    readfile($zipCaminho);

    // Apaga o zip temporário depois do envio
    unlink($zipCaminho);
    exit;
} else {
    echo "Erro ao criar o arquivo ZIP.";
}