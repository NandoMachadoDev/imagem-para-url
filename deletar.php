<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['caminho'])) {
    $caminho = $_POST['caminho'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica se a imagem pertence ao usuário
    $stmt = $conn->prepare("SELECT id FROM imagens WHERE caminho = ? AND usuario_id = ?");
    $stmt->bind_param("si", $caminho, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        // Apaga do banco
        $stmt = $conn->prepare("DELETE FROM imagens WHERE caminho = ? AND usuario_id = ?");
        $stmt->bind_param("si", $caminho, $usuario_id);
        $stmt->execute();


        // Apaga do sistema de arquivos
        if (file_exists($caminho)) {
            unlink($caminho);
        }

        header("Location: historico.php");
        exit;
    } else {
        echo "❌ Você não tem permissão para apagar esta imagem.";
    }
} else {
    echo "❌ Requisição inválida.";
}