<?php

session_start();


require_once 'conexao.php';

$quantidadeGerada = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trem = (int) $_POST['trem'];
    $quantidadeGerada = (int) $_POST['quantidade'];
    $erros = [];
    
    if ($id_trem <= 0) {
        $erros[] = 'Selecione um trem válido.';
    }
    if ($quantidadeGerada <= 0 || $quantidadeGerada > 200) {
        $erros[] = 'Informe uma quantidade de sensores entre 1 e 200.';
    }
    if (count($erros) === 0) {
        
    $stmt = $conexao ->prepare('INSERT INTO leitura_sensor(fk_id_trem,data_hora,velocidade_kmh,temperatura_motor_c,consumo_litro_hora,vibracao_mm_s ) VALUES(?,?,?,?,?,?)');
   
    $momento = time() -quantidadeGerada * 300;

    for ($i = 0; $i < $quantidadeGerada; $i++) {
        $dataHora = date('Y-m-d H:i:s', $momento);
        $velocidade = rand(0, 9000) /100;
        $temperatura = rand(6000, 11500) / 100;
        $consumo = rand(2000, 9000) /100;
        $vibracao = rand(50, 900) /100;

        $stmt->bind_param('isdddi', $id_trem, $dataHora, $velocidade, $temperatura, $consumo, $vibracao);
        if (!$stmt->execute()) {
            $_SESSION['mensagem'] = 'Erro ao gerar leitura: ' . $stmt->error;
            break;
        

        $stmt->bind_param('isdddd', $id_trem, $dataHora, $velocidade, $temperatura, $consumo, $vibracao);
        $stmt->execute();
        }

        $stmt->close();

        $_SESSION['mensagem'] = $quantidade  .'Leitura gerada com sucesso.';

        header('Location: leituras.php?id_trem=' . $id_trem);
        exit;
    }
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

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

    <?php
    if ($mensagem !==""):
        ?>
        <p class="aviso"><?= htmlspecialchars($mensagem) ?></p>
    <?php
    endif;
    ?>
    <?php
    if (isset($erros) && count($erros) > 0):
        ?>
        <div class="aviso aviso-erro">
            <ul>
            <?php
            foreach ($erros as $erro):
                ?>
                <li><?= htmlspecialchars($erro) ?></li>
                <?php
                endforeach

                ?>
                </ul>

            </div>
            <?php
            endif;
    ?>

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
