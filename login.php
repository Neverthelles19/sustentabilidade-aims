<?php
// Inclui o arquivo de conexão com o banco de dados
require_once('conexao.php'); 

// Variáveis de erro e sucesso
$erro = "";
$sucesso = "";

// Processa o formulário quando ele é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação de dados
    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos!";
    } else {
        // Verificar se o e-mail existe no banco de dados
        $sql = "SELECT id_usuarios, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Se o e-mail não for encontrado
        if ($stmt->num_rows == 0) {
            $erro = "E-mail não cadastrado!";
        } else {
            // O e-mail existe, agora vamos verificar a senha
            $stmt->bind_result($id_usuarios, $senha_banco);
            $stmt->fetch();

            // Verifica se a senha fornecida corresponde à armazenada no banco
            if (password_verify($senha, $senha_banco)) {
                // Inicia a sessão do usuário
                session_start();
                $_SESSION['user_id'] = $id_usuarios;
                $_SESSION['email'] = $email;

                // Redireciona para a página principal após login
                header("Location: dashboard.php");
                exit; // Evita execução do código abaixo
            } else {
                $erro = "Senha incorreta!";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
      <link rel="stylesheet" rel="login.css">
</head>
<body class="bg-gray-50 text-gray-900">

    <header class="bg-blue-600 text-white py-6">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-semibold">Login</h1>
        </div>
    </header>

    <main class="max-w-lg mx-auto p-6 mt-10 bg-white rounded-lg shadow-md">
        <!-- Exibe erros -->
        <?php if ($erro): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="senha" class="block text-sm font-medium text-gray-700">Senha:</label>
                <input type="password" id="senha" name="senha" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Entrar</button>
        </form>

        <p class="mt-4 text-center text-sm">
            Ainda não tem uma conta? <a href="cadastro.php" class="text-blue-600 hover:text-blue-800">Cadastre-se aqui</a>
        </p>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy;AIMS 2024 Análise Econômica Social. </p>
        </div>
    </footer>

    <style>
        footer {
    text-align: center;
    background-color: #007bff;
    color: white;
    padding: 0px;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
    margin-top: 20px;
    text-align: center;
    width: 100%;
    margin-top: 10px;
  }
  </style>

</body>
</html>
