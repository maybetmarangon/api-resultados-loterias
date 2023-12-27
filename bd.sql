CREATE TABLE resultados_loteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_do_jogo VARCHAR(255) NOT NULL,
    numero_do_jogo INT NOT NULL,
    data_sorteio DATETIME NOT NULL,
    valor_premio DECIMAL(10, 2) NOT NULL,
    dezenas_sorteadas TEXT,
    trevo_da_sorte TEXT
);
