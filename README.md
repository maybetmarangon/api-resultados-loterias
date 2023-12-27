# Loterias API

## Importante!
> Esse script é para uso pessoal; ele captura dados dos resultados dos jogos das loterias do Brasil (MegaSena, Quina, Lotofácil e +Milionária) e armazena em um banco de dados MySQL, em seguida você pode usar o "resultado.php" para receber um json de todos os jogos ou de algum em particular.

## Instalação
Crie um novo banco de dados MySQL e faça o upload do bd.sql
Coloque as páginas update.php e resultados.php no seu servidor, modifique as credenciais de acesso para o banco de dados e pronto.

## Em breve:
- Novos jogos
- Requisição de um jogo específico
- Mais dados sobre cada jogo

### Uso
Para receber todos os últimos resultados:

```
seuservidor/resultados.php

```

Para receber os dados de um jogo específico:

```
seuservidor/resultados.php?jogo={nome_do_jogo}
```

jogos disponíveis:

> Os valores retornados irão mudar de loteria para loteria, respeitanto a natureza de cada um delas.

| Jogo | {nome_do_jogo} |
| ------ | ------ |
| ```Mega-Sena ``` | mega-sena |
| ```Quina ``` | quina |
| ```+Milionária ``` | mais-milionaria |
| ```Lotofácil ``` | lotofacil |
| ```Lotomania ``` | em breve |
| ```Dupla Sena ``` | em breve |
| ```Loteca ``` | em breve |
| ```Loteria Federal ``` | em breve |
| ```Timemania ``` | em breve |
| ```Dia de Sorte ``` | em breve |
| ```Super Sete ``` | em breve |