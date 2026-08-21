<?php

session_start();

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $excluirId = (int) $_POST['excluir_id'];

    $stmt = $conexao->prepare('DELETE FROM trens WHERE id_trem = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = 'Trem excluído com sucesso.';
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir o trem: ' . $stmt->error;
    }

    $stmt->close();
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

$resultado = $conexao->query('SELECT * FROM trens ORDER BY prefixo_trem');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <span class="marca">Frota Ferroviaria</span>
    </header>

    <main>
        <div class=titulo>
            <h1>Trens cadastrados</h1>
            <a href="" class="botao botao-primario">Novo trem</a>
        </div>

        <?php
        if ($resultado->num_rows === 0):
        ?>
            <p class="Vazio">Nenhum trem cadastrado.</p>
        <?php
        else:
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Prefixo</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Capacidade</th>
                        <th>Situação</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($trem = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($trem['prefixo_trem']) ?></td>
                            <td><?= htmlspecialchars($trem['modelo_trem']) ?></td>
                            <td><?= (int) $trem['ano_fabricacao'] ?></td>
                            <td><?= number_format((float) $trem['capacidade_tonelas'], 2, ',', '.') ?> t </td>
                            <td>
                                <span class="etiqueta etiqueta-<?= htmlspecialchars($trem['situacao_trem']) ?>"
                                    <?= htmlspecialchars($trem['situacao_trem']) ?>
                                    </span>
                            </td>
                            <td class="acoes">
                                <a href="formulario.php?id=<?= (int) $trem['id_trem'] ?>" class="botao botao-secundario">Editar</a>

                                <form method="post" onsubmit="return" confirm ('Confirma a exclusão deste trem?');">
                                    <input types="hidden" name="excluir_id" values="<?=
                                                                                    (int) $trem['id_trem'] ?>">
                                    <button type="submit" class="botao botao-perigo">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>

</html>