# CCPBL1
Repositório dedicado ao problema 1 da disciplina de Concorrência e Conectividade

## Requisitos

- PHP (pode ser instalado via xampp)

- É necessário também ir no diretório em que o php instalado e procurar o arquivo "php.ini", nele é necessário incluir "extension=php_sockets.dll" às extensões, se, por algum motivo, esse dll não existir ele poder ser encontrado em <a href="https://www.php.net/downloads.php"> php.net</a>

## Organização das pastas

A solução está organizada em 4 pastas e dois arquivos, com o Diagrama de Sequência básico da arquitetura.

- Na Pasta **Controllers** está o controlador da home, a página do médico, responsável por fazer contato com o servidor, pegar os dados e retornar para a página..
- Na pasta **css** está um arquivo .css apenas para estilização da página.
- Na pasta **models** está presente todos os modelos necessários para executar a solução, sendo dois executáveis via PHP, sendo eles: o cliente "PatientClient.php"  e o servidor "Server.php".
- Na pasta **view** está o arquivo .html chamado Home que representa a página do médico.

## Como executar

Para executar a ordem em que os arquivos são executados não irá influenciar no final, pois as conexões poderão ser estabelecidas quando todos estiverem executando, porém aqui será dita a melhor forma de executar os arquivos, **desde que todos estejam com os dados de IP e Porta corretos**.

- Passo 1: Execute o Server via PHP, assim o Server estará disponível, esperando requisições.
- Passo 2: Execute o a página do médico (Isso pode ser feito em PHP utilizando o comando na pasta raiz do projeto "php -S IP:PORTA"), assim a página está já estará fazendo requisições **GET** ao Servidor, buscando os pacientes que estejam armazenados.
- Passo 3: Execute os clientes via PHP, assim cada cliente estará fazendo requisições **POST**, para pacientes novos, e **PUT**, para pacientes que já estão cadastrados.

Após esses passos, a solução estará funcionando.
