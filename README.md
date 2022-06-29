# Recipes Blog example

A Symfony example project made with CQRS, Hexagonal Architecture and DDD

## Deploy

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. In docker-compose.override.yml choose between dev or prod environment
3. Run `docker-compose build --pull --no-cache` to build fresh images
4. Run `docker-compose up` (the logs will be displayed in the current shell)
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `docker-compose down --remove-orphans` to stop the Docker containers.
