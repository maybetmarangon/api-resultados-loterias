<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "usuario_banco_de_dados";
$password = "senha_banco_de_dados";
$dbname = "banco_de_dados";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Define o modo de erro do PDO como exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o parâmetro "jogo" está presente na URL
    if (isset($_GET['jogo'])) {
        $jogo = $_GET['jogo'];
        
        // Query SQL para selecionar registros do jogo específico
        $sql = "SELECT * FROM resultados_loteria WHERE nome_do_jogo = :jogo ORDER BY data_sorteio DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':jogo', $jogo);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se houverem resultados, convertemos para JSON e exibimos
        if ($results) {
            header('Content-Type: application/json');
            echo json_encode($results);
        } else {
            echo json_encode(['message' => 'Nenhum dado encontrado para o jogo: ' . $jogo]);
        }
    } else {
        // Query SQL para selecionar o último registro de cada jogo
        $sql = "SELECT * FROM resultados_loteria WHERE (nome_do_jogo, data_sorteio) IN 
                (SELECT nome_do_jogo, MAX(data_sorteio) FROM resultados_loteria GROUP BY nome_do_jogo)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se houverem resultados, convertemos para JSON e exibimos
        if ($results) {
            header('Content-Type: application/json');
            echo json_encode($results);
        } else {
            echo json_encode(['message' => 'Nenhum dado encontrado para os jogos']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}

// Fecha a conexão com o banco de dados
$conn = null;
?>
