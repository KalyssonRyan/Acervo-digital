<?php 
require 'function.php'; 

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o botão "Adicionar" foi pressionado
    if (isset($_POST["submit"]) && $_POST["submit"] == "add") {
        // Processa o envio da imagem e retorna o nome do arquivo salvo
        $file_name = add($_FILES["file"]);
    }
}
?>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <li><a href="addimage.php">Enviar Fotos</a></li>
            <li><a href="index.php">Visualizar fotos</a></li>
            </ul>
        </nav> 
        <h1>Acervo Digital</h1>
        <p>Preencha os dados e armazene as suas fotos</p>
    </header>
    <form class="" action="" method="post" enctype="multipart/form-data">
        <div class="send">
            <p>Nome</p>
            <input type="text" name="name" required><br>
            <input type="file" name="file" id="file" onchange="previewImage()" required>
            <!-- <h2>Imagem Carregada</h2> -->
            <img id="preview" src="#" alt="Imagem Carregada" style="display: none; max-width: 100%; height: auto;">
            <p>Descrição</p>
            <input type="text" name="description" required><br>
            <p>Data</p>
            <input type="date" name="date" required><br>
            <button type="submit" name="submit" value="add" class="button-30" role="button">Adicionar</button>
        </div>
    </form>
    <br>

    <script>
        function previewImage() {
            var preview = document.getElementById('preview');
            var fileInput = document.getElementById('file');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
