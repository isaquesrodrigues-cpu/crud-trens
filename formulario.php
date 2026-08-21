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