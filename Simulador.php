<?php

session_start();


require_once 'conexao.php';

$trens = $conexao->query('SELECT * FROM trens ORDER BY prefixo_trem');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de sensores</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <span class="marca">Frota Ferroviaria</span>
    </header>

<main>
    <h1>Simulador de sensores</h1>

    <form method="POST">
<div class="linha">
    <div class="campo">
        <label for="trem">Trem:</label>
        <select type="trem" name="trem">
        <option value="">Selecione um trem</option>
        <?php
        while ($trem = trens ->fetch_assoc()) :
        ?>
        <option value="<?= $trem['id_trem'] ?>"><?= htmlspecialchars($trem['prefixo_trem']) ?> - <?= htmlspecialchars($trem['modelo_trem']) ?>
    </option>
    <?php
    endwhile;
    ?>
    </select>
        </div>
        <div class="campo">
            <label for="quantidade">Quantidade de sensores:</label>
            <input type="number" name="quantidade" id="quantidade" min="1" max="200" value="50">
        </div>
    </div>
    <div class="acoes">
        <button type="submit" class="botao botao-primario">Gerar leitura</button>
</form>
</main>
</body>
</html>
