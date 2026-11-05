<?php session_start(); if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') { header("Location: /login.php"); exit; } require_once "../../config/conexao.php"; $id = $_GET["id"] ?? null; if (!$id) { header("Location: produtos.php"); exit; } /* |-------------------------------------------------------------------------- | BUSCAR PRODUTO |-------------------------------------------------------------------------- */ $stmt = $conexao->prepare( "SELECT * FROM produtos WHERE id = ?" ); $stmt->bind_param("i", $id); $stmt->execute(); $resultado = $stmt->get_result(); $produto = $resultado->fetch_assoc(); if (!$produto) { header("Location: produtos.php"); exit; } /* |-------------------------------------------------------------------------- | ATUALIZAR PRODUTO |-------------------------------------------------------------------------- */ if ($_SERVER["REQUEST_METHOD"] == "POST") { $nome = $_POST["nome"]; $descricao = $_POST["descricao"]; $preco = $_POST["preco"]; $estoque = $_POST["estoque"]; $categoria = $_POST["categoria"]; /* | Mantém a imagem atual caso nenhuma nova seja enviada */ $imagem = $produto['imagem']; /* |-------------------------------------------------------------------------- | UPLOAD DA NOVA IMAGEM |-------------------------------------------------------------------------- */ if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) { $arquivo = $_FILES["imagem"]; $extensao = strtolower( pathinfo($arquivo["name"], PATHINFO_EXTENSION) ); /* | Extensões permitidas */ $extensoes_permitidas = [ "jpg", "jpeg", "png", "webp" ]; if (!in_array($extensao, $extensoes_permitidas)) { die("Formato de imagem inválido. Use JPG, JPEG, PNG ou WEBP."); } /* | Cria um nome único para a imagem */ $novo_nome = uniqid("produto_", true) . "." . $extensao; /* | Caminho da pasta de imagens */ $pasta = $_SERVER["DOCUMENT_ROOT"] . "/assets/img/"; /* | Caminho completo do arquivo */ $destino = $pasta . $novo_nome; /* | Move a imagem para a pasta */ if (!move_uploaded_file($arquivo["tmp_name"], $destino)) { die("Erro ao enviar a imagem."); } /* | Exclui a imagem antiga, caso exista | e caso ela seja uma imagem enviada pelo sistema. */ if (!empty($produto["imagem"])) { $imagem_antiga = $pasta . $produto["imagem"]; if (file_exists($imagem_antiga)) { unlink($imagem_antiga); } } /* | Guarda o novo nome no banco */ $imagem = $novo_nome; } /* |-------------------------------------------------------------------------- | ATUALIZAR NO BANCO |-------------------------------------------------------------------------- */ $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria = ?, imagem = ? WHERE id = ?"; $stmt = $conexao->prepare($sql); $stmt->bind_param( "ssdissi", $nome, $descricao, $preco, $estoque, $categoria, $imagem, $id ); $stmt->execute(); header("Location: produtos.php"); exit; } ?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Editar Produto - PetLove</title>

<link
    rel="stylesheet"
    href="../../assets/css/crud-produtos.css"
>

</head>

<body>

<div class="container">

<h1>
    Pet<span>Love</span>
</h1>


<h2>
    Editar produto
</h2>


<form
    method="POST"
    enctype="multipart/form-data"
>


    <input
        type="text"
        name="nome"
        value="<?= htmlspecialchars($produto['nome']) ?>"
        placeholder="Nome do produto"
        required
    >


    <input
        type="text"
        name="descricao"
        value="<?= htmlspecialchars($produto['descricao'] ?? '') ?>"
        placeholder="Descrição"
    >


    <input
        type="number"
        name="preco"
        value="<?= htmlspecialchars($produto['preco']) ?>"
        placeholder="Preço"
        step="0.01"
        min="0"
        required
    >


    <input
        type="number"
        name="estoque"
        value="<?= htmlspecialchars($produto['estoque']) ?>"
        placeholder="Quantidade em estoque"
        min="0"
        required
    >


    <select
        name="categoria"
        required
    >

        <option
            value="Ração"
            <?= $produto['categoria'] == 'Ração' ? 'selected' : '' ?>
        >
            Ração
        </option>


        <option
            value="Brinquedos"
            <?= $produto['categoria'] == 'Brinquedos' ? 'selected' : '' ?>
        >
            Brinquedos
        </option>


        <option
            value="Higiene"
            <?= $produto['categoria'] == 'Higiene' ? 'selected' : '' ?>
        >
            Higiene
        </option>


        <option
            value="Acessórios"
            <?= $produto['categoria'] == 'Acessórios' ? 'selected' : '' ?>
        >
            Acessórios
        </option>


        <option
            value="Petiscos"
            <?= $produto['categoria'] == 'Petiscos' ? 'selected' : '' ?>
        >
            Petiscos
        </option>

    </select>


    <?php if (!empty($produto['imagem'])): ?>

        <div class="imagem-atual">

            <p>
                Imagem atual:
            </p>

            <img
                src="../../assets/img/<?= htmlspecialchars($produto['imagem']) ?>"
                alt="<?= htmlspecialchars($produto['nome']) ?>"
                style="max-width: 180px; border-radius: 10px; margin: 10px 0;"
            >

        </div>

    <?php endif; ?>


    <label>
        Nova imagem (opcional)
    </label>


    <input
        type="file"
        name="imagem"
        accept=".jpg,.jpeg,.png,.webp"
    >


    <small>
        Se não escolher uma nova imagem, a imagem atual será mantida.
    </small>


    <button type="submit">
        Salvar alterações
    </button>


    <a
        href="produtos.php"
        class="voltar"
    >
        ← Voltar
    </a>


</form>

</div>

</body>

</html>