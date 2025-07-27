<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do NetoFramework</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px; /* Adicionado para espaçar entre as seções se houver várias */
        }
        h1, h2 {
            text-align: center;
            color: #0056b3;
            margin-top: 30px; /* Para espaçar o segundo título */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            color: #888;
            margin-top: 20px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background-color: #e9e9e9;
        }
        .error {
            color: #d9534f;
            font-weight: bold;
        }
        .back-link {
            display: block;
            margin-top: 30px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #007bff;
            border-radius: 5px;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        .back-link:hover {
            background-color: #007bff;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dados de Solicitações de Orçamento da NetoFramework.</h1>

        <?php
        // Configurações do Banco de Dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "neto_framework"; // Confirme que este nome está correto

        // Cria a conexão com o banco de dados
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("<p class='no-data error'>Erro na conexão com o banco de dados: " . $conn->connect_error . "</p>");
        }

        // Define o conjunto de caracteres
        $conn->set_charset("utf8mb4");

        // Query para selecionar todos os dados da tabela de orçamentos
        $sql_orcamentos = "SELECT id, nome, email, telefone, mensagem, data_envio FROM solicitacoes_orcamento ORDER BY id DESC";
        $result_orcamentos = $conn->query($sql_orcamentos);

        if ($result_orcamentos->num_rows > 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nome</th>";
            echo "<th>Email</th>";
            echo "<th>Telefone</th>";
            echo "<th>Mensagem</th>";
            echo "<th>Data de Envio</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while($row = $result_orcamentos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nome"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . ($row["telefone"] ? $row["telefone"] : 'N/A') . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($row["mensagem"])) . "</td>";
                echo "<td>" . $row["data_envio"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='no-data'>Nenhum orçamento foi recebido ainda.</p>";
        }
        // NÃO FECHAR A CONEXÃO AINDA, POIS VAMOS USÁ-LA PARA A PRÓXIMA TABELA
        ?>
    

       
    </div>

    <a href="index.html" class="back-link">Voltar para o site principal</a>
</body>
</html>