<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nome_usuario'])) {
    http_response_code(403);
    echo "Acesso negado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $usuario_id = $_SESSION['usuario_id'];

    // Garante um nome de pasta seguro e sanitizado
    $nome_usuario = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $_SESSION['nome_usuario']);
    $pastaUsuario = "uploads/" . $nome_usuario;

    // Cria a pasta do usuário se não existir
    if (!is_dir($pastaUsuario)) {
        mkdir($pastaUsuario, 0777, true);
    }

    $imagem = $_FILES['image'];
    $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
    $nomeImagem = uniqid("img_") . "." . strtolower($extensao);
    $caminhoRelativo = "$pastaUsuario/$nomeImagem";

    if (move_uploaded_file($imagem['tmp_name'], $caminhoRelativo)) {
        // Salva no banco de dados
        $stmt = $conn->prepare("INSERT INTO imagens (usuario_id, caminho) VALUES (?, ?)");
        $stmt->bind_param("is", $usuario_id, $caminhoRelativo);
        $stmt->execute();

        // Gera a URL pública
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $caminhoApp = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $url = $protocolo . $host . $caminhoApp . '/' . $caminhoRelativo;

        echo $url;
    } else {
        echo "Erro ao salvar a imagem.";
    }
}