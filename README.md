
# Task Manager

Este projeto usa o Laravel Sail para configurar o ambiente de desenvolvimento local com Docker. Siga as instruções abaixo para inicializar o sistema.

## Pré-requisitos

- Docker e Docker Compose instalados na sua máquina.
- Laravel Sail (Sail é uma dependência de desenvolvimento do Laravel que fornece um ambiente Docker fácil de usar).

## Instalação

1. **Clone o repositório**

  ```bash
   git clone git@github.com:victor18edu/task-manager.git
   cd task-manager
  ``` 

2. **Execute o Composer dentro de um contêiner Docker**

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

3. **Copie o arquivo .env.example**
```
cp .env.example .env
```

4. **Rode o builder do sail**

```
./vendor/bin/sail up -d --build
```

5. **Gere a key da aplicação**
```
./vendor/bin/sail artisan key:generate
```

6. **Instale as dependências do NPM**
```
./vendor/bin/sail npm install
```

7. **Rode as migrations e seeds**
```
./vendor/bin/sail artisan migrate --seed
```

8. **Compile os assets**
```
./vendor/bin/sail npm run build
```

9. **Rode os testes (Opcional)**
```
./vendor/bin/sail artisan test
```

## Acesso ao sistema

A aplicação estará disponível em: http://localhost

O painel do Mailpit (emails fake): http://localhost:8025

O phpMyAdmin (acesso ao banco): http://localhost:8080

## Usuário Padrão
Um usuário administrador é criado automaticamente pelas seeds com as seguintes credenciais:

- Email: admin@admin.com

- Senha: Admin123!


