<?php 
require 'function.php';

// Verifica se o formulário de pesquisa foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Executa a função de pesquisa e obtém os resultados
    $searchResults = search($conn);
} else {
    // Define os resultados da pesquisa como vazios
    $searchResults = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <title>Acervo Digital</title>
</head>
<body>
    <header>
       <nav>
            <ul>
            <li><a href="addimage.php">Enviar Fotos</a></li>
            <li><a href="index.php">Visualizar fotos</a></li>
            </ul>
        </nav> 
    
        <h1>Banco de Imagens</h1>
        <p>Busque aqui as suas imagens</p>
    </header>    
    <div id="imagem">
        <form method="post" action="index.php"> <!-- Adiciona action e method -->
            <div class="input-wrapper">
                <label for="search">Pesquise</label>
                <input type="text" name="search" required>
                
                <button type="submit" name="submit" value="search" class="button-30" role="button">Pesquisar</button>
            </div>    
        </form>  
        <?php
        // Inicializa a variável $i
        $i = isset($_POST['i']) ? intval($_POST['i']) : 1;

        // Verifica se o botão "Próximo" foi clicado
        if (isset($_POST['next'])) {
            $i++;
        }

        // Verifica se o botão "Anterior" foi clicado e se $i é maior que 1
        if (isset($_POST['previous']) && $i > 1) {
            $i--;
        }
        
        ?>
        <?php
        if (!empty($searchResults)) {
            echo '<h1 id="indice">Resultado da pesquisa</h1>'; // Exibe o <h1>
            // Exibe os resultados da pesquisa
            foreach ($searchResults as $result) {
                // Verifica se a chave 'html' está definida em cada elemento do array
                if (isset($result['html'])) {
                    echo $result['html']; // Exibe o HTML do resultado
                } else {
                    echo "<p>Nenhum resultado encontrado</p>";
                    break; // Para o loop assim que encontrar um elemento sem a chave 'html'
                }
        
                // Verifica se a chave 'id' está definida em cada elemento do array
                if (isset($result['id'])) {
                    // Adiciona um botão para editar cada imagem
                    echo "<a href='edit.php?id={$result['id']}' class='button-30'>Editar</a>";
                }
            }
        } else {
            // Se $searchResults estiver vazio, exibe uma mensagem de nenhum resultado encontrado
            echo "<p>Nenhum resultado encontrado</p>";
        }
        
            // ultimo codigo que funcionou -------------------
            // if (!empty($searchResults)) {
            //     echo '<h1 id="indice">Resultado da pesquisa</h1>'; // Exibe o <h1>
            //     // Exibe os resultados da pesquisa
            //     foreach ($searchResults as $result) {
            //         echo $result['html']; // Exibe o HTML do resultado
            //         // Adiciona um botão para editar cada imagem
            //         echo "<a href='edit.php?id={$result['id']}' class='button-30'>Editar</a>";
            //     }
            
            // }
            // else{
            //     echo "<p>Nenhum resultado encontrado</p>";
            // }
            



            //  if (!empty($searchResults)) {
            //     echo '<h1 id="indice">Resultado da pesquisa</h1>'; // Exibe o <h1>
            //     echo $searchResults; // Exibe os resultados da pesquisa
            //     echo "<a href='edit.php?id={$id}' class='button-30'>Editar</a>";
                    
            // }




            // $startIndex = strpos($searchResults, 'id=');

            // if ($startIndex !== false) {
            //     // Adicione o comprimento da string 'id=' para obter o índice de onde o ID realmente começa
            //     $startIndex += strlen('id=');

            //     // Encontre o índice onde o ID termina (geralmente quando atinge um espaço em branco ou aspas)
            //     $endIndex = strpos($searchResults, '"', $startIndex);

            //     // Extrai o ID da substring
            //     $id = substr($searchResults, $startIndex, $endIndex - $startIndex);
            
            //     // Agora você pode usar $id como um número inteiro em seu link
            //     echo "<a href='edit.php?id={$id}' class='button-30'>Editar</a>";
        
            // } else {
            //     // Se o ID não for encontrado, exiba uma mensagem ou faça outra ação adequada
            //     echo "ID não encontrado nos resultados da pesquisa.";
            // }
            
            
        ?>


        <h1 id="indice">Navegação por Índice</h1>
        <?php
        
        exibir($conn,$i);
        ?>
        <form method="post">
            <!-- Botão para a imagem anterior -->
            <input type="hidden" name="i" value="<?php echo $i; ?>">
            <button type="submit" class="button-30" name="previous" <?php echo ($i <= 1) ? 'disabled' : ''; ?>>Anterior</button>
            <a href="edit.php?id=<?php echo $i; ?>" class="button-30">Editar</a>
            <!-- Link para editar a imagem -->

            <!-- Botão para a próxima imagem -->
            <input type="hidden" name="i"  value="<?php echo $i; ?>">
            <button type="submit" name="next" class="button-30">Próximo</button>
        </form>
        
         <!-- Exibe os resultados abaixo do formulário -->
    </div>

    
</body>
</html>
