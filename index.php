<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM imagens WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$contagem = $result->fetch_assoc()['total'];


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Imagem para URL</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
    .dropzone {
        border: 2px dashed #555;
        padding: 60px;
        color: #ccc;
        margin: 40px auto;
        text-align: center;
        width: 80%;
        background-color: #1e1e1e;
        border-radius: 10px;
    }

    #urlResultado {
        margin-top: 20px;
        font-weight: bold;
        word-break: break-all;
        background-color: #2c2c2c;
        padding: 15px;
        border-radius: 6px;
    }

    .nav {
        text-align: right;
        margin-bottom: 20px;
    }

    .nav a {
        margin-left: 20px;
    }
    </style>
</head>

<body>
    <p>👤 Olá, <strong><?= $_SESSION['nome_usuario'] ?></strong> – você tem <strong><?= $contagem ?></strong> imagens
        salvas.</p>
    <div class="nav">
        <a href="historico.php">📜 Histórico</a>
        <a href="logout.php">🔒 Sair</a>
    </div>

    <h2>Cole uma imagem (Ctrl+V)</h2>

    <div class="dropzone" contenteditable="true" id="pasteZone">
        Pressione <strong>Ctrl + V</strong> aqui com um print copiado da tela
    </div>

    <div id="urlResultado"></div>

    <script>
    const pasteZone = document.getElementById('pasteZone');
    const resultado = document.getElementById('urlResultado');

    pasteZone.addEventListener('paste', function(e) {
        const items = e.clipboardData.items;
        for (let item of items) {
            if (item.type.indexOf("image") === 0) {
                const file = item.getAsFile();
                const formData = new FormData();
                formData.append("image", file);

                fetch("upload.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.text())
                    .then(url => {
                        resultado.innerHTML = `
    <p>Imagem salva com sucesso:</p>
    <input type="text" id="linkImagem" value="${url}" readonly style="width:100%; max-width:500px;">
    <button onclick="copiarLink()">📋 Copiar link</button>
`;
                    })
                    .catch(err => {
                        resultado.innerHTML = "❌ Erro ao enviar a imagem.";
                    });
            }
        }
    });
    </script>
    <script>
    function copiarLink() {
        const input = document.getElementById("linkImagem");
        input.select();
        input.setSelectionRange(0, 99999); // Para dispositivos móveis
        document.execCommand("copy");
        alert("Link copiado para a área de transferência!");
    }
    </script>
</body>

</html>