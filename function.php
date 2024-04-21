<?php
$conn = mysqli_connect("localhost", "root", "", "acervo");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    if ($_POST["submit"] == "add") {
        add();
    } else if (isset($_POST["edit"])) {
        edit($conn);
    } else if ($_POST["submit"] == "search") {
        search($conn);
    
}
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_edit"])) {
    // Chamar a função edit para processar os dados do formulário de edição
    edit($conn);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_delete"])){
    delete($conn);
}




function add()
{
    global $conn;

    if (isset($_POST["name"], $_POST["description"], $_FILES["file"])) {
        $name = $_POST["name"];
        $filename = $_FILES["file"]["name"];
        $description = $_POST["description"];
        $date=$_POST["date"];
        $tmpName = $_FILES["file"]["tmp_name"];

        if (!empty($filename)) {
            $newfilename = uniqid() . "-" . $filename;

            move_uploaded_file($tmpName, 'uploads/' . $newfilename);

            // Corrigindo a consulta SQL para usar prepared statements
            $query = "INSERT INTO fotos (nome, imagem, descricao,data_imagem) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ssss', $name, $newfilename, $description,$date);
            mysqli_stmt_execute($stmt);
            if (isset($file_name)) {
                echo "Nome do arquivo salvo: " . $file_name; // Verifica se o nome do arquivo está sendo retornado corretamente
            }
            
            echo "<script> alert('Imagem Adicionada com sucesso!'); window.location.href = 'addimage.php';</script>";
            exit; // Sai do script para evitar que o restante do código seja executado
        } else {
            echo "<script> alert('Por favor, selecione um arquivo!');</script>";
        }
    }
}
function exibir($conn, $i) {  
    $result = mysqli_query($conn, "SELECT * FROM fotos WHERE id=$i");
    
    // Verifica se a consulta retornou algum resultado
    if ($result && mysqli_num_rows($result) > 0) {
        // Obtém os dados da imagem
        $imagem = mysqli_fetch_assoc($result);
        
        
        
        
        // Obtém a descrição da imagem
        $descricao = $imagem['descricao'];
        $data = $imagem['data_imagem'];
        $data_formatada = date("d/m/Y", strtotime($data));
        // Exibe a imagem e a descrição
        echo "<img src='uploads/{$imagem["imagem"]}' width='500' padding='50px'>";
        echo "<p>Descrição : {$descricao}</p>";
        echo "<p>Data: {$data_formatada}</p>";
    } else {
        // Se não houver imagem com o ID fornecido
        echo "Nenhuma imagem encontrada.";
    }
}
 //função search que funciona
// function search($conn){
//     $search = mysqli_real_escape_string($conn, $_POST["search"]);
//     $query = "SELECT * FROM fotos WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%' OR data_imagem LIKE '%$search%' OR id LIKE '%$search%'";
//     $result = mysqli_query($conn, $query);
    
//     if ($result && mysqli_num_rows($result) > 0) {
//         $searchResults = "<div id='search-results'>"; // Inicia a div de resultados
//         while ($row = mysqli_fetch_assoc($result)) {
//             $descricao = $row['descricao'];
//             $data = $row['data_imagem'];
//             $id=$row['id'];
//             $data_formatada = date("d/m/Y", strtotime($data));
//             // Monta o HTML dos resultados da pesquisa
//             $searchResults .= "<img src='uploads/{$row["imagem"]}' width='500' padding='50px'>";
//             $searchResults .= "<p>Descrição : {$descricao}</p>";
//             $searchResults .= "<p>Id : {$id}</p>";
//             $searchResults .= "<p>Data: {$data_formatada}</p>";
//             $searchResults .= "<hr>";
//         }
//         $searchResults .= "</div>"; // Fecha a div de resultados
//     } else {
//         // Se não houver imagem com o termo de pesquisa fornecido
//         $searchResults = "Nenhuma imagem encontrada.";
//     }
//     // Retorna os resultados da pesquisa como uma string
//     return $searchResults;
// }


function search($conn){
    $search = mysqli_real_escape_string($conn, $_POST["search"]);
    $query = "SELECT * FROM fotos WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%' OR data_imagem LIKE '%$search%' OR id LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
    
    $searchResults = array(); // Inicializa um array para armazenar os resultados
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $descricao = $row['descricao'];
            $data = $row['data_imagem'];
            $id = $row['id']; // Obtém o ID da imagem
            $data_formatada = date("d/m/Y", strtotime($data));
            
            // Monta o HTML dos resultados da pesquisa
            $htmlResult = "<div id='search-results'>"; // Inicia a div de resultados
            $htmlResult .= "<img src='uploads/{$row["imagem"]}' width='500' padding='50px'>";
            $htmlResult .= "<p>Descrição : {$descricao}</p>";
            $htmlResult .= "<p>Id : {$id}</p>";
            $htmlResult .= "<p>Data: {$data_formatada}</p>";
            $htmlResult .= "<hr>";
            $htmlResult .= "</div>"; // Fecha a div de resultados
            
            // Adiciona os dados da imagem ao array de resultados
            $searchResults[] = array(
                'html' => $htmlResult, // Armazena o HTML
                'id' => $id,
                'descricao' => $descricao,
                'data_formatada' => $data_formatada
                // Adicione outros dados da imagem conforme necessário
            );
        }
    } else {
        // Se não houver imagem com o termo de pesquisa fornecido
        $searchResults[] = array("Nenhuma imagem encontrada.");
    }
    // Retorna os resultados da pesquisa como um array associativo
    return $searchResults;
}

function edit($conn)
{
    // Verifica se os dados do formulário de edição foram submetidos
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_edit"])) {
        // Obtém os dados do formulário
        $id = $_POST["edit_id"];
        $nome = $_POST["edit_nome"];
        $descricao = $_POST["edit_descricao"];
        $data = $_POST["edit_data"];

        // Atualiza as informações da imagem no banco de dados
        $query = "UPDATE fotos SET nome=?, descricao=?, data_imagem=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        
        // Verifica se a preparação da declaração foi bem-sucedida
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssi', $nome, $descricao, $data, $id);
            $result = mysqli_stmt_execute($stmt);
            
            // Verifica se a execução da declaração foi bem-sucedida
            if ($result) {
                echo "<script> alert('Imagem atualizada com sucesso!'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script> alert('Erro ao atualizar banco'); window.location.href = 'index.php';</script>";
            }
        } else {
            echo "<script> alert('Erro ao preparar declaração SQL'); window.location.href = 'index.php';</script>";
        }
    }
}


function delete($conn)
{
    
    $id = $_POST["edit_id"];

    $query= "DELETE FROM fotos WHERE id=$id";
    mysqli_query($conn,$query);
    echo "
    
    <script>
        alert('Imagem deletada com sucesso!');
        window.location.href ='index.php';
    
    </script>
    ";


}
