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

    $key = 'aHR0cHM6Ly9tcy5zb3J0ZW9ubGluZS5jb20uYnIvcmVzdWx0cy1jb25uZWN0b3IvdjEvYXBpLXJlc3VsdHMvbGVnYWN5Lw==';

    // Função para descriptografar a URL
    function decrypt_url($key) {
        return base64_decode($key);
    }

    // Descriptografa a URL
    $api = decrypt_url($key);

    $response = file_get_contents($api);

    // Decodificando o JSON recebido
    $data = json_decode($response, true);

    // Preparando a instrução SQL para verificar e inserir os dados
    $stmt = $conn->prepare("INSERT INTO resultados_loteria (nome_do_jogo, numero_do_jogo, data_sorteio, valor_premio, dezenas_sorteadas, trevo_da_sorte) 
                            VALUES (:nome_do_jogo, :numero_do_jogo, :data_sorteio, :valor_premio, :dezenas_sorteadas, :trevo_da_sorte)
                            ON DUPLICATE KEY UPDATE 
                            nome_do_jogo = VALUES(nome_do_jogo),
                            numero_do_jogo = VALUES(numero_do_jogo),
                            data_sorteio = VALUES(data_sorteio),
                            valor_premio = VALUES(valor_premio),
                            dezenas_sorteadas = VALUES(dezenas_sorteadas),
                            trevo_da_sorte = VALUES(trevo_da_sorte)");

    // Iterando pelos dados filtrados e inserindo no banco de dados
    foreach (["mega-sena", "quina", "lotofacil", "mais-milionaria"] as $game) {
        if (isset($data[$game])) {
            $dezenas_sorteadas = isset($data[$game]['dozens']) ? json_encode($data[$game]['dozens']) : null;
            $trevo_da_sorte = ($game === "mais-milionaria" && isset($data[$game]['clovers'])) ? json_encode($data[$game]['clovers']) : null;

            // Verificar se os resultados já existem no banco de dados
            $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM resultados_loteria WHERE data_sorteio = :data_sorteio AND numero_do_jogo = :numero_do_jogo");
            $checkStmt->bindParam(':data_sorteio', $data[$game]['drawDateTime']);
            $checkStmt->bindParam(':numero_do_jogo', $data[$game]['contestNumber']);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] == 0) {
                // Vinculando os parâmetros e executando a consulta preparada
                $stmt->bindParam(':nome_do_jogo', $game);
                $stmt->bindParam(':numero_do_jogo', $data[$game]['contestNumber']);
                $stmt->bindParam(':data_sorteio', $data[$game]['drawDateTime']);
                $stmt->bindParam(':valor_premio', $data[$game]['contestPrize']);
                $stmt->bindParam(':dezenas_sorteadas', $dezenas_sorteadas);
                $stmt->bindParam(':trevo_da_sorte', $trevo_da_sorte);
                $stmt->execute();
            } else {
                echo "Os dados para o jogo '$game' já estão atualizados.\n";
            }
        }
    }

    echo "Dados inseridos/atualizados no banco de dados com sucesso!";
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Fecha a conexão com o banco de dados
$conn = null;
?>
