<?php
// Configurações da conexão
$host = "localhost";
$user = "root";
$password = ""; // ajuste se tiver senha
$database = "MeuBanco";

// Criar conexão
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$sql = "SELECT Id, Nome, NIF, Telefone, Email, Observacoes FROM Clientes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Sistema de Gestão Agrícola</title>
    <style>
        /* Reset básico */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f9f9;
            color: #333;
            min-height: 100vh;
        }
        header {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        header h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            letter-spacing: 1.2px;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.7;
            font-style: italic;
        }
        header .db-name {
            margin-top: 5px;
            font-weight: 600;
            font-size: 1rem;
            color: #f39c12;
        }
        main {
            max-width: 1100px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.15);
        }
        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.8rem;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }
        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tr:hover {
            background-color: #f1f9ff;
        }
        td {
            vertical-align: middle;
        }
        p.no-data {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
            margin-top: 40px;
        }
        @media (max-width: 700px) {
            header h1 {
                font-size: 2rem;
            }
            main {
                margin: 20px 15px;
                padding: 20px;
            }
            th, td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Gestão Agrícola</h1>
        <p>Desenvolvido por NetoFramework</p>
        <div class="db-name">Banco de Dados: <?= htmlspecialchars($database) ?></div>
    </header>
    <main>
        <h2>Lista de Clientes</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>NIF</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["Id"]) ?></td>
                            <td><?= htmlspecialchars($row["Nome"]) ?></td>
                            <td><?= htmlspecialchars($row["NIF"]) ?></td>
                            <td><?= htmlspecialchars($row["Telefone"]) ?></td>
                            <td><?= htmlspecialchars($row["Email"]) ?></td>
                            <td><?= nl2br(htmlspecialchars($row["Observacoes"])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Nenhum cliente encontrado.</p>
        <?php endif; ?>

    </main>
</body>
</html>
<?php $conn->close(); ?>
