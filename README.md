# Teste prático First Decision

## Descrição
Cadastro de usuários. 

- Linguagem utilizada: PHP 8.4
- Framework utilizado: Laravel 12
- Banco de dados: Postgresql 15

## Requisitos

- PHP 8.4
- Docker
- Postgresql 

## Setup

### Iniciar banco de dados

Acesse a pasta postgresql/.docker e crie o arquivo `.env` com as configurações do banco de dados.

```sh
cd postgresql\.docker
cp .env-sample .env
```
#### Usando o docker
> Se for utilizar docker siga os passos abaixo:

Buildar a imagem
```sh
docker compose build
```

Subir o container
```sh
docker compose up
```

#### Usando o client postgresql
Caso tenha instalado o postgresql, apenas configure o arquivo `config/database.php`

### Migration
Execute o comando abaixo para rodar a migration do banco:

```sh
php artisan migrate
```

### Iniciar a aplicação

Suba o projeto através do composer 
```sh
cd user-project

composer run dev
```

A aplicação conta com um formulário de cadastro de usuários.
Os dados são enviados via formulário na interface, utilizando a requisição POST do PHP.

Também é possível utilizar a API disponível.

Acesse o caminho abaixo:

```sh
curl -X POST http://localhost:8000/api/user \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{
        "name": "Luke Skywalker",
        "email": "skywalker.luke@firstdecision.com",
        "password": "secret-pass",
        "password_confirmation": "secret-pass"
    }'
```
