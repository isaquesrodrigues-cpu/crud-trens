<?php


session_start();

require_once 'conexao.php';

$id = (int) ($_GET['id'] ?? 0);

$prefixo = '';
$modelo = '';
$ano_fabricacao = '';
$capacidade_toneladas = '';
$situacao = ['ativo', 'inativo' =>'inativo', 'manutencao' => 'Em manutenção'];
$situacao = 'ativo';
$erros = [];

if( $id >0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conexao->prepare('SELECT * FROM trens WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $trem = $stmt->get_result()->fetch_assoc();
    $stmt->close();


    if ($trem) {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        header('Location: index.php');
        exit;
    }
    $prefixo = $trem['prefixo_trem'];
    $modelo = $trem['modelo_trem'];
    $ano_fabricacao = $trem['ano_fabricacao'];
    $capacidade_toneladas = $trem['capacidade_toneladas'];
    $situacao = $trem['situacao_trem'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $prefixo = trim($_POST['prefixo']);
    $modelo = trim($_POST['modelo']);
    $ano_fabricacao = trim($_POST['ano_fabricacao']);
    $capacidade_toneladas = trim($_POST['capacidade_toneladas']);
    $situacao = $_POST['situacao'];
    
    if ($prefixo === '') {.
        $erros[] = 'Informe o prefixo do trem.';
    }

    if ($modelo === '') {
        $erros[] = 'Informe o modelo do trem.';
    }
    if ($ano_fabricacao === '') {
        $erros[] = 'Informe o ano de fabricação do trem.';
    }
    if (!is_numeric($ano_fabricacao) || $ano_fabricacao <=1900 || $ano_fabricacao > 2100) {
        $erros[] = 'Informe um ano de fabricação válido.';
    }
    if ($capacidade_toneladas === '') {
        $erros[] = 'Informe a capacidade do trem em toneladas.';

    }
    if (!isset($situacoes[$situacao])) {
        $erros[] = 'Informe uma situação válida.';
    }
    if (count($erros) === 0) {
        $ano_fabricacao = (int) $ano_fabricacao;
        $capacidade_toneladas = (float) $capacidade_toneladas

        if ($id > 0) {
            $stmt = $conexao->prepare('UPDATE trens SET prefixo_trem = ?, modelo_trem = ?, ano_fabricacao = ?, capacidade_toneladas = ?, situacao = ? WHERE id = ?');
            %stmt->bind_param('ssdisi', $prefixo, $modelo, $ano_fabricacao, $capacidade_toneladas, $situacao, $id);
            $stmt->execute();

            if ($stmt ->is_executable) {
                $_SESSION['mensagem'] = 'Trem atualizado com sucesso.';
            } else {
                $_SESSION['mensagem'] = 'Não foi possivel atualizar os dados.';
            }
} else{
    $stmt = $conexao->prepare('INSERT INTO trens (prefixo_trem, modelo_trem, ano_fabricacao, capacidade_toneladas, situacao) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('ssdis', $prefixo, $modelo, $ano, $capacidade, $situacao);
    $stmt-> ($stmt -> execute()); {
        $_SESSION['mensagem'] = 'Trem cadastrado com sucesso.';
    } else {
        $_SESSION['mensagem'] = 'Não foi possivel cadastrar o trem.';
    }
}
$stmt-> close();

header('Location: index.php');
exit;


}

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id > 0 ? 'editar trem' : 'novo trem' ?></title>
<link rel="stylesheet" href="style.css">  
</head>
<body>
    <heder>
        <span class="marca">Frota Ferroviária</span>
</header>
<main>
    <h1><?= $id > 0 ? 'Editar trem' : 'Novo trem' ?></h1>

    <?php 
    if (count($erro) > 0 ): ?>

    <div class='aviso aviso-erro'>
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
            </ul>
            </div>
            <?php endif; ?>

    <form method="post" action="salvar.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="linha">
            <div class="campo">
                <label for="prefixo">Prefixo</label>
                <input type="text" name="prefixo" id="prefixo" value="<?= $prefixo ? htmlspecialchars($prefixo) : '' ?>" maxlength="20" required>
            </div>
            <div class="campo">
                <label for="ano_fabricacao">Ano de Fabricação</label>
             <input type="number" name="ano_fabricacao" id="ano_fabricacao" min="1900" max="2100" value="<?= htmlspecialchars((string)$ano_fabricacao) : '' ?>" required>
            
            </div>
        </div>
        <div class="campo">
            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" value="<?= $modelo ? htmlspecialchars($modelo) : '' ?>" maxlength="80" required>
        </div>
        <div class= "linha">
            <div class="campo">
                <label for="capacidade_toneladas">Capacidade (t)</label>
                <input type="number" id= "capaciade_toneladas"
                name="capacidade_toneladas " step="0.01" min="0.01" value="<?= htmlspecialchars ((string) $capacidadetoneladas) ?>">
</div>
<div class="campo">
<label for="situacao">Situação</label>
<select id="situacao" name="situacao" required>
<?php
foreach ( $situacoes as $chave => $rotulo): ?>
    <option value="<?= $chave ?>" <?= $chave === $situacao ? 'selected' : '' ?>>
        <?= $rotulo ?>
    </option>
<?php 

endforeach;

?>




</select>
</div>
</div>
<div class="acoes">
    <button type="submit" class="botao botao_primario"><?=  $id > 0 ? 'Atualizar' : 'Cadastrar' ?></button>
    <a href="index.php" class="cotao botao_secundario">Cancelar</a>
</div>
    </form>
</main>
</body>
</html>