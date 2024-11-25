<?php
require_once('conexao.php');

// Variáveis de erro e sucesso
$erro = "";
$sucesso = "";

// Processa o formulário quando ele é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação de dados (por exemplo, verificando se o e-mail já está registrado)
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        // Criptografa a senha para armazenamento seguro no banco de dados
        $senha_cripto = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara a consulta SQL para inserir o usuário no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha_cripto);

        // Executa a consulta e verifica se a inserção foi bem-sucedida
        if ($stmt->execute()) {
            $sucesso = "Cadastro realizado com sucesso!";
            // Redireciona o usuário para a página de login (opcional)
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar usuário: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900">

    <header class="bg-blue-600 text-white py-6">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-semibold">Cadastro de Usuário</h1>
        </div>
    </header>

    <main class="max-w-lg mx-auto p-6 mt-10 bg-white rounded-lg shadow-md">
        <!-- Exibe erros ou sucesso -->
        <?php if ($erro): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <?php echo $erro; ?>
            </div>
        <?php elseif ($sucesso): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form action="cadastro.php" method="POST">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome:</label>
                <input type="text" id="nome" name="nome" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="senha" class="block text-sm font-medium text-gray-700">Senha:</label>
                <input type="password" id="senha" name="senha" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cadastrar</button>
        </form>

        <p class="mt-4 text-center text-sm">
            Já tem uma conta? <a href="login.php" class="text-blue-600 hover:text-blue-800">Faça login aqui</a>
        </p>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Análise Econômica Social.AIMS</p>
        </div>
    </footer>

</body>
</html>

