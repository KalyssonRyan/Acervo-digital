
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="edit.css">

</head>
<?php 
require 'function.php';

// Verifica se o ID da imagem foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepara a declaração SQL
    $stmt = mysqli_prepare($conn, "SELECT * FROM fotos WHERE id=?");
    
    // Verifica se a preparação da declaração foi bem-sucedida
    if ($stmt) {
        // Vincula o parâmetro de ID à declaração
        mysqli_stmt_bind_param($stmt, 'i', $id);
        
        // Executa a declaração
        mysqli_stmt_execute($stmt);
        
        // Obtém o resultado da consulta
        $result = mysqli_stmt_get_result($stmt);
        
        // Verifica se a consulta retornou algum resultado
        if ($result && mysqli_num_rows($result) > 0) {
            // Obtém os dados da imagem
            $imagem = mysqli_fetch_assoc($result);
            
            // Exibe a imagem e o formulário de edição
            // Código do formulário de edição aqui
            echo "<div class=send>";
            echo "<form method='post' enctype='multipart/form-data'>";
            echo "<img src='uploads/{$imagem["imagem"]}' width='600px' padding='50px'>";
            echo "<input type='hidden' name='edit_id' value='$id'>"; // Campo oculto para o ID da imagem
            echo "<p>Nome:</p>";
            echo "<input type='text' name='edit_nome' id='edit_nome' value='{$imagem["nome"]}'>";
            echo "<p>Descrição:</p>";
            echo "<textarea name='edit_descricao' id='edit_descricao'>{$imagem["descricao"]}</textarea>";
            echo "<p>Data</p>";
            echo "<input type='date' name='edit_data' id='edit_data' value='{$imagem["data_imagem"]}'>";
            echo "<button type='submit' name='submit_edit' value='edit' class='button-30'>Salvar Edições</button>";
            echo "<button type='submit' name='submit_delete' value='delete' class='button-30'>Excluir</button>";
            echo "</form>";
            echo "</div>";
        } else {
            // Se não houver imagem com o ID fornecido
            echo "Nenhuma imagem encontrada.";
        }
    } else {
        // Se houver um erro ao preparar a declaração SQL
        echo "Erro ao preparar a declaração SQL.";
    }
} else {
    // Se nenhum ID de imagem foi passado na URL
    echo "ID de imagem não fornecido.";
}
?>