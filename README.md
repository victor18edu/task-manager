
# Task Manager

Este projeto usa o Laravel Sail para configurar o ambiente de desenvolvimento local com Docker. Siga as instruções abaixo para inicializar o sistema.

## Pré-requisitos

- Docker e Docker Compose instalados na sua máquina.
- Laravel Sail (Sail é uma dependência de desenvolvimento do Laravel que fornece um ambiente Docker fácil de usar).

## Instalação

1. **Clone o repositório**

  ```bash
   git clone https://github.com/seu-usuario/task-manager.git
   cd seu-repositorio
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

4. **Rode o builder do sail**

```
./vendor/bin/sail up -d --build
```

5. **Rode as migrations e as seeds**
```
./vendor/bin/sail artisan migrate --seed
```

6. **instale o npm**
```
./vendor/bin/sail npm install
```

7. **rode o builder do npm**
```
./vendor/bin/sail npm run dev
```

8. **Rode os testes (Opcional)**
```
./vendor/bin/sail artisan test
```
