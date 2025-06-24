<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
// Conta quantas imagens o usuário tem
$stmtContagem = $conn->prepare("SELECT COUNT(*) as total FROM imagens WHERE usuario_id = ?");
$stmtContagem->bind_param("i", $_SESSION['usuario_id']);
$stmtContagem->execute();
$resContagem = $stmtContagem->get_result();
$totalImagens = $resContagem->fetch_assoc()['total'];


$stmt = $conn->prepare("SELECT caminho, criado_em FROM imagens WHERE usuario_id = ? ORDER BY criado_em DESC");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Histórico</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="container">
        <h2>Histórico de Imagens</h2>
        <a href="index.php">← Voltar</a>
        <ul>
            <?php while ($img = $resultado->fetch_assoc()): ?>
            <li>
                <img src="<?= $img['caminho'] ?>" width="100"><br>
                <?= $img['criado_em'] ?><br>
                <a href="<?= $img['caminho'] ?>" target="_blank"><?= $img['caminho'] ?></a><br><br>
                <form method="POST" action="deletar.php"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?');">
                    <input type="hidden" name="caminho" value="<?= $img['caminho'] ?>">
                    <button type="submit">🗑️ Excluir</button>
                </form>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php if ($totalImagens > 0): ?>
        <form action="baixar_todas.php" method="post" style="margin-bottom: 20px;">
            <button type="submit">📦 Baixar todas as imagens</button>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>