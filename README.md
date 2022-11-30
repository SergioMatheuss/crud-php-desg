# CRUD DE ESCOLAS

Aplicação Web do tipo monolítica criada com:
-PHP para o backEnd 7^4;
-MYSQL/MariaDB para o banco de dados;
-HTML, CSS e JavaScript pro FRONT-END; 

##Funcionalidades:

-CRUD de Alunos;
-CRUD de Professores;
-CRUD de Usuários;
-CRUD de Categorias;
-CRUD de Cursos.

## Passo a passo para rodar o projeto.
Certifique-se que seu computador tem os softwares instalados;
-PHP;
-MySQL ou MariaDB;
-Editor de texto(por exemplo o VS code);
-Navegador Web;
-Composer (Gerenciador de pacotes do PHP);


### Clone o projeto.
Baixe ou faça o clone do repositorio:
`git clone https://github.com/SergioMatheuss/crud-php-desg`,
após isso entre no diretório que foi gerado
`cd crud-php-desg`

#### Habilitar as extensões do PHP.
Abra o diretório de instalação do PHP, encontre o arquivo *php.ini-production*, renomeio-o para *php.ini* e abra-o com algum editor de texto.

Encontre as seguintes linhas e descomente-as, são essas:

-pdo_mysql;

-curl; 

-mb_string;

-openssl;

#### Instalar as dependências.

Dentro do diretório da aplicação execute no terminal:
`composer install`

Certifique-se que um diretório chamado **/vendor** foi criado.

### Banco de Dados.

> O banco de dados é relacional e contém as tabelas com até 2 níveis de normatização. 

#### Criando o banco de dados.

Entre no seu cliente de banco de dados, e execute o comando:

```sql 
CREATE DATABASE db_escola;
```

#### Migrar a estrutura do banco de dados.

Ainda dentro do diretório da aplicação, copie e cole o conteúdo do arquivo **db.sql** e execute.

Certifique-se que as tabelas foram criadas, executando o comando

```sql 
SHOW TABLES;
```

Se o resultado for a lista de tabelas existentes, fique feliz, você conseguiu!

#### Configure as credenciais de acesso.
Encontre o arquivo **/config/database.php** e edite-o conforme as credenciais do seu usuário do banco de dados.

### Executando a aplicação.
Para executar e testar a aplicação, dentro do terminal, execute:

`php -S localhost:8000 -t public`

Agora acesse o endereço `http://localhost:8000` em seu navegador.
