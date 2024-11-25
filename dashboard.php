<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    // Se não estiver, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Caso o usuário esteja logado, você pode mostrar o conteúdo da página
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Comunidade Financeira</title>
    <!-- CDN do Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css"></script>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <header class="bg-blue-600 text-white text-center py-6">
        <h1 class="text-4xl font-bold">Orçamento Pessoal</h1>
    </header>
    <nav>
    <a href="dashboard.php">Início</a>
    <a href="orcamento.php">orçamento</a>
    <a href="dicas.html">Dicas</a>
    <a href="politicas_publicas.html">Politicas públicas</a>
    <a href="artigo.html">Artigos</a>
</nav>

    <main class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mt-6">Inovação e Sustentabilidade: Análise Econômica e Social</h2>

        <h3 class="text-xl font-medium text-gray-700 mt-4">Bem-vindo ao seu painel, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h3>

        <p class="text-gray-600 mt-4">
            Agora você pode ajudar sua comunidade a entender melhor sua situação econômica e alcançar a independência financeira.
        </p>

        <div class="mt-8">
            <h3 class="text-xl font-medium text-gray-800">Como você pode ajudar:</h3>
            <ul class="list-disc ml-6 text-gray-600">
                <li><a href="dicas.html" class="text-blue-600 hover:text-blue-800">Acesse As Dicas de Economia</a></li>
            </ul>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-medium text-gray-800">Recursos educativos:</h3>
            <ul class="list-disc ml-6 text-gray-600">
                <li><a href="artigo.html" class="text-blue-600 hover:text-blue-800">Leia Artigos sobre Finanças Pessoais</a></li>
                <li><a href="politicas_publicas.html" class="text-blue-600 hover:text-blue-800">Politicas públicas</a></li>
            </ul>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-medium text-gray-800">Organize seus Gastos:</h3>
            <p class="text-gray-600">
                Quer controlar melhor suas finanças? <a href="orcamento.php" class="text-blue-600 hover:text-blue-800">Clique aqui para organizar seus gastos</a>.
            </p>
        </div>

        <div class="mt-8">
            <!-- Link para logout -->
            <p class="text-gray-600">
                <a href="logout.php" class="text-blue-600 hover:text-blue-800">Sair</a>
            </p>
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; 2024 Comunidade Financeira. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
