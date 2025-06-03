## About Project
This project provides a Notification Management API built with Laravel. It allows users to send notifications, which are then consumed by a separate Fastify (Node.js) service.

### Technologs
- Backend (Laravel PHP)
- Queue System: RabbitMQ
- Caching: Redis
- Database: MySQL

## Requires
Make sure the following are installed or available via Docker:
- PHP ^8.3
- Mysql
- Redis
- Rabbitmq

## Getting Started with Docker
- With Docker
    - Goto the main project directory
    - Copy .env.example file to .env
    - Create a docker network so resource can shared between laravel and nodejs
    - Run command to create network: docker network create golballyhub
    - Run command to run docker container: docker compose up -d
    - Once all services are running, access the application at: [goto](http://localhost:8000)

## API Usage
This Laravel project exposes APIs for sending notifications.
Notifications sent through this system are queued and consumed by a Fastify (Node.js) service.

## Postman Collection
- You can find the Postman collection in the root directory of the project: laravel-notification-api.postman_collection.json
- Use this file to test the available API endpoints.

## Notes
- Notification route is protected by sanctum auth so you need to register and login first to get personal access token (pta).