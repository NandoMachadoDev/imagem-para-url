<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];

        // Pegando o nome do usuário (ou e-mail se preferir)
        $stmt_nome = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
        $stmt_nome->bind_param("i", $usuario['id']);
        $stmt_nome->execute();
        $res_nome = $stmt_nome->get_result();
        $dados_nome = $res_nome->fetch_assoc();

        $_SESSION['nome_usuario'] = $dados_nome['nome']; // ou use $email se preferir

        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
        <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    </div>
</body>

</html>