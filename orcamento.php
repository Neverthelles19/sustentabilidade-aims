<?php
// Inicia a sessão
session_start();

$conn = new mysqli("localhost", "root", "", "inovacao");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializa variáveis
$erro = "";
$sucesso = "";
$moeda_selecionada = "BRL"; // Padrão é o Real Brasileiro

// Processa o formulário quando ele é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera o ID do usuário logado a partir da sessão
    $id_usuario = $_SESSION['user_id'];  // Certifique-se de que o 'user_id' está armazenado corretamente na sessão

    // Coleta os dados do formulário
    $alimentacao = $_POST['alimentacao'];
    $transporte = $_POST['transporte'];
    $lazer = $_POST['lazer'];
    $moradia = $_POST['moradia'];
    $outros = $_POST['outros'];
    $renda = $_POST['renda'];
    $moeda = $_POST['moeda']; // A moeda selecionada pelo usuário

    // Valida se a moeda está entre as opções permitidas
    $moedas_validas = ['BRL', 'USD', 'KRW'];
    if (!in_array($moeda, $moedas_validas)) {
        $erro = "Moeda inválida!";
        exit;
    }

    // Valida se os campos foram preenchidos
    if (empty($alimentacao) || empty($transporte) || empty($lazer) || empty($moradia) || empty($outros) || empty($renda)) {
        $erro = "Por favor, preencha todos os campos!";
    } elseif (!is_numeric($alimentacao) || !is_numeric($transporte) || !is_numeric($lazer) || !is_numeric($moradia) || !is_numeric($outros) || !is_numeric($renda)) {
        $erro = "Por favor, insira valores numéricos válidos!";
    } else {
        // Calcula o total de gastos e o saldo restante
        $total_gastos = $alimentacao + $transporte + $lazer + $moradia + $outros;
        $saldo = $renda - $total_gastos;

        // Prepara a consulta SQL para inserir os dados
        $sql_insert = "INSERT INTO orcamento_mes (alimentacao, transporte, lazer, moradia, outros, renda, moeda, total_gastos, saldo, id_usuarios) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepara a declaração para evitar injeções SQL
        if ($stmt = $conn->prepare($sql_insert)) {
            
            // Faz o binding dos parâmetros
            $stmt->bind_param(
                "dddddsdddi",
                $alimentacao,
                $transporte,
                $lazer,
                $moradia,
                $outros,
                $renda,
                $moeda,
                $total_gastos,
                $saldo,
                $id_usuario
            );

            // Executa a consulta
            if ($stmt->execute()) {
                // Armazena os dados do orçamento na sessão para exibição
                $_SESSION['orcamento'] = [
                    'alimentacao' => $alimentacao,
                    'transporte' => $transporte,
                    'lazer' => $lazer,
                    'moradia' => $moradia,
                    'outros' => $outros,
                    'renda' => $renda,
                    'moeda' => $moeda,
                    'total_gastos' => $total_gastos,
                    'saldo' => $saldo
                ];

                $sucesso = "Orçamento calculado e salvo com sucesso!";
            } else {
                $erro = "Erro ao salvar o orçamento: " . $stmt->error;
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            $erro = "Erro ao preparar a consulta: " . $conn->error;
        }

        $conn->close();
    }
}

// Função para formatar os valores de acordo com a moeda escolhida
function formatarMoeda($valor, $moeda) {
    switch ($moeda) {
        case 'USD':
            return "$" . number_format($valor, 2, '.', ',');
        case 'KRW':
            return "₩" . number_format($valor, 0, '', ',');
        case 'BRL':
        default:
            return "R$ " . number_format($valor, 2, ',', '.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento Pessoal</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="orca.css">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

</head>
<body class="bg-gray-100">

<header class="bg-blue-600 text-white py-6">
<h1 class="text-3xl font-semibold">Orçamento Pessoal</h1>
        <a href="logout.php"  class="btn btn-log" title="Sair"><span class="material-symbols-outlined">logout</span></a>
    </header>

    <nav class="opcoes">
      <a href="dashboard.php" class="hover:text-yellow-400">Início</a>
      <a href="orcamento.php" class="hover:text-yellow-400">Orçamento</a>
      <a href="dicas.html" class="hover:text-yellow-400">Dicas</a>
      <a href="politicas_publicas.html" class="hover:text-yellow-400"
        >Políticas públicas</a
      >
        <a href="artigo.html">Artigos</a>
    </nav>
    <div class="container" id="navegacao">
         <a href="dashboard.php" title="voltar" class="btn"><span class="material-symbols-outlined">
arrow_back_ios
</span></a>        
         <div id="mensagem">
                    <!-- Exibe erro -->
        <?php if ($erro): ?>
            <div class="alert alert-error text-red-500 bg-red-100 p-4 rounded-lg mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <!-- Exibe sucesso -->
        <?php if ($sucesso): ?>
            <div class="alert alert-success text-green-500 bg-green-100 p-4 rounded-lg mb-4">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>
        </div>            
        </div>
    <main class="container">
    <div class="container">

<!-- Formulário para inserir gastos -->
<form action="orcamento.php" method="POST" class="space-y-4">
<h1>Análise de orçamento</h1>
<fieldset>
    <legend>            <label for="moeda" class="block text-lg font-medium">Escolha a Moeda:</label></legend>

<select name="moeda" id="moeda" required class="w-full p-2 border border-gray-300 rounded-md">
    <option value="BRL" <?php echo (isset($moeda_selecionada) && $moeda_selecionada == 'BRL') ? 'selected' : ''; ?>>Real (R$)</option>
    <option value="USD" <?php echo (isset($moeda_selecionada) && $moeda_selecionada == 'USD') ? 'selected' : ''; ?>>Dólar (USD)</option>
    <option value="KRW" <?php echo (isset($moeda_selecionada) && $moeda_selecionada == 'KRW') ? 'selected' : ''; ?>>Won Coreano (₩)</option>
</select>
</fieldset>

    <fieldset>
        <legend><label for="alimentacao" class="block text-lg font-medium">Alimentação:</label></legend>
        
        <input type="number" id="alimentacao" name="alimentacao" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>

    <fieldset>
        <legend> <label for="transporte" class="block text-lg font-medium">Transporte:</label></legend>
    
        <input type="number" id="transporte" name="transporte" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>

    <fieldset>
        <legend><label for="lazer" class="block text-lg font-medium">Lazer:</label></legend>
        
        <input type="number" id="lazer" name="lazer" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>

    <fieldset>
        <legend> <label for="moradia" class="block text-lg font-medium">Moradia:</label></legend>
    
        <input type="number" id="moradia" name="moradia" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>

    <fieldset>
        <legend><label for="outros" class="block text-lg font-medium">Outros:</label></legend>
        <input type="number" id="outros" name="outros" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>

    <fieldset>
        <legend> <label for="renda" class="block text-lg font-medium">Renda:</label></legend>
    
        <input type="number" id="renda" name="renda" required class="w-full p-2 border border-gray-300 rounded-md">
    </fieldset>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

</div>

<button type="submit" class="bg-blue-600 text-white p-2 rounded-md mt-4">Calcular</button>
</form>
</div>
<section class="container" id="resumo">

<!-- Exibe o gráfico com os dados de orçamento -->
<?php if (isset($_SESSION['orcamento'])): ?>
        <div class="resumo-grafico">
    <h2 class="text-xl font-medium mt-8">Gráfico de Orçamento</h2>
    <canvas id="orcamentoChart" class="chart" style="width:200px"></canvas>
    <script>
        const canvas = document.getElementById('orcamentoChart');

        // Define as dimensões desejadas
        canvas.width = 1200;  // Largura em pixels
        canvas.height = 1200; // Altura em pixels

        const ctx = canvas.getContext('2d');

        const chartData = {
            labels: ['Alimentação', 'Transporte', 'Lazer', 'Moradia', 'Outros'],
            datasets: [{
                label: 'Gastos Mensais',
                data: [
                    <?php echo $_SESSION['orcamento']['alimentacao']; ?>,
                    <?php echo $_SESSION['orcamento']['transporte']; ?>,
                    <?php echo $_SESSION['orcamento']['lazer']; ?>,
                    <?php echo $_SESSION['orcamento']['moradia']; ?>,
                    <?php echo $_SESSION['orcamento']['outros']; ?>
                ],
                backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FFD700', '#8A2BE2'],
                borderColor: ['#FF5733', '#33FF57', '#3357FF', '#FFD700', '#8A2BE2'],
                borderWidth: 1
            }]
        };

        const myChart = new Chart(ctx, {
            type: 'pie',
            data: chartData
        });
    </script>
</div>
<?php endif; ?>   

<div class="resumo-tabela">
<!-- Verifica se os dados estão na sessão -->
<?php if (isset($_SESSION['orcamento']) && 
    isset($_SESSION['orcamento']['alimentacao']) && 
    isset($_SESSION['orcamento']['transporte']) && 
    isset($_SESSION['orcamento']['lazer']) && 
    isset($_SESSION['orcamento']['moradia']) && 
    isset($_SESSION['orcamento']['outros']) && 
    isset($_SESSION['orcamento']['renda']) && 
    isset($_SESSION['orcamento']['total_gastos']) && 
    isset($_SESSION['orcamento']['saldo']) && 
    isset($_SESSION['orcamento']['moeda'])): ?>

<!-- Tabela com os resultados -->
<h2 class="text-xl font-medium mt-8">Resumo do Orçamento</h2>
<table class="table-auto w-full mt-4 border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-300 p-2">Categoria</th>
            <th class="border border-gray-300 p-2">Valor</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border border-gray-300 p-2">Alimentação</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['alimentacao'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr>
            <td class="border border-gray-300 p-2">Transporte</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['transporte'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr>
            <td class="border border-gray-300 p-2">Lazer</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['lazer'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr>
            <td class="border border-gray-300 p-2">Moradia</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['moradia'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr>
            <td class="border border-gray-300 p-2">Outros</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['outros'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr>
            <td class="border border-gray-300 p-2">Renda</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['renda'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr class="font-semibold">
            <td class="border border-gray-300 p-2">Total de Gastos</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['total_gastos'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
        <tr class="font-semibold">
            <td class="border border-gray-300 p-2">Saldo Restante</td>
            <td class="border border-gray-300 p-2"><?php echo formatarMoeda($_SESSION['orcamento']['saldo'], $_SESSION['orcamento']['moeda']); ?></td>
        </tr>
    </tbody>
</table>
<?php else: ?>
<!-- Caso os dados não estejam na sessão, você pode exibir uma mensagem -->
<p>Não há dados de orçamento disponíveis.</p>
<?php endif; ?>
</div>


</section>    </main>

</body>
</html>