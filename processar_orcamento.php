<?php
// Define o tipo de conteúdo da resposta como JSON.
header('Content-Type: application/json');

// --- ATENÇÃO: ESTAS LINHAS ESTÃO ATIVADAS PARA DEBUG. DESATIVE EM AMBIENTE DE PRODUÇÃO! ---
// Elas farão com que erros PHP sejam exibidos na resposta, o que é crucial para depurar.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- FIM DA SEÇÃO DE DEBUG ---

// Configurações do Banco de Dados MySQL (XAMPP padrão)
$servername = "localhost"; // Geralmente 'localhost' para o XAMPP
$username = "root";        // Nome de usuário padrão do MySQL no XAMPP
$password = "";            // Senha padrão do MySQL no XAMPP (geralmente vazia)
$dbname = "neto_framework"; // ***CERTIFIQUE-SE DE QUE ESTE NOME É EXATAMENTE O MESMO QUE VOCÊ CRIOU NO PHPmyAdmin!***

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão com o banco de dados falhou
if ($conn->connect_error) {
    // Retorna uma mensagem de erro JSON se a conexão não for estabelecida
    echo json_encode([
        'success' => false,
        'message' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error
    ]);
    exit(); // Termina a execução do script
}

// Define o conjunto de caracteres para a conexão, para garantir que acentos e caracteres especiais sejam tratados corretamente
$conn->set_charset("utf8mb4");

// Verifica se a requisição HTTP foi feita usando o método POST (ou seja, o formulário foi enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tenta decodificar o corpo da requisição como JSON. O seu JavaScript usa Fetch API e envia JSON.
    $data = json_decode(file_get_contents('php://input'), true);

    // Como fallback, se o JSON não for decodificado ou estiver vazio, tenta pegar os dados de $_POST
    // (Útil se o cliente não enviar JSON, mas o seu JS atual envia).
    if (empty($data)) {
        $data = $_POST;
    }

    // Pega os dados dos campos do formulário usando os 'name' dos inputs HTML
    // E sanitiza os dados para evitar SQL Injection (real_escape_string)
    $nome = isset($data['name']) ? $conn->real_escape_string($data['name']) : '';
    $email = isset($data['email']) ? $conn->real_escape_string($data['email']) : '';
    // O campo 'phone' não é obrigatório no HTML, então pode ser nulo.
    // Se estiver vazio, passamos 'null' para o banco de dados (se a coluna permitir NULL).
    $telefone = isset($data['phone']) && !empty($data['phone']) ? $conn->real_escape_string($data['phone']) : null;
    $mensagem = isset($data['message']) ? $conn->real_escape_string($data['message']) : '';

    // Validação dos campos obrigatórios (conforme definido no seu HTML como 'required')
    if (empty($nome) || empty($email) || empty($mensagem)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos obrigatórios (Nome Completo, Email Corporativo, Descrição do Projeto).'
        ]);
        $conn->close();
        exit();
    }

    // Prepara a query SQL para inserir os dados na tabela 'solicitacoes_orcamento'.
    // Usamos Prepared Statements para segurança (evitar SQL Injection) e performance.
    // Os '?' são placeholders para os valores que serão inseridos.
    // As colunas (nome, email, telefone, mensagem) DEVEM corresponder EXATAMENTE aos nomes das colunas na sua tabela MySQL.
    $sql = "INSERT INTO solicitacoes_orcamento (nome, email, telefone, mensagem)
            VALUES (?, ?, ?, ?)";

    // Prepara a instrução SQL para execução
    $stmt = $conn->prepare($sql);

    // Verifica se houve um erro na preparação da query (ex: nome de tabela ou coluna errado)
    if ($stmt === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao preparar a query SQL: ' . $conn->error
        ]);
        $conn->close();
        exit();
    }

    // Associa os valores das variáveis aos placeholders '?' na query preparada.
    // 'ssss' indica os tipos de dados dos parâmetros: s=string, s=string, s=string, s=string.
    // Aqui estamos tratando 'telefone' como string também.
    $stmt->bind_param("ssss", $nome, $email, $telefone, $mensagem);

    // Executa a instrução preparada
    if ($stmt->execute()) {
        // Se a execução foi bem-sucedida
        echo json_encode([
            'success' => true,
            'message' => ''
        ]);
    } else {
        // Se a execução da query falhou (ex: erro de tipo de dado, coluna não existe)
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao enviar sua solicitação: ' . $stmt->error
        ]);
    }

    // Fecha a instrução preparada
    $stmt->close();

} else {
    // Se a requisição não for POST (alguém tentou acessar o processar_orcamento.php diretamente)
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido. Por favor, envie o formulário de orçamento.'
    ]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>