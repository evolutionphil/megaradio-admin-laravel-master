# Megaradio Admin

## Local Development Setup with Docker

This guide will help you set up a local development environment using Docker. 
The project uses an external database, so make sure the place it in `.env` file.

### Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop) installed on your local machine.
- Access to the external database (credentials and connection details).

### Getting Started

1. **Clone the repository & CD into it**

    ```sh
    cd your-project
    ```

2. **Create a `.env` file**

   Create a `.env` file in the root directory of the project and add the following environment variables. Replace the placeholders with your actual database credentials and other configuration details.

    ```shell
    cp .env.example .env
    ```

3. **Build the Docker image**

   Build the Docker image for the project.

    ```sh
    docker build -t mg-admin .
    ```

4. **Run the Docker container**

   Run the Docker container, making sure to pass the `.env` file to it.

    ```sh
    docker run -v ./:/app -p 8000:8000 --name mg-admin mg-admin
    ```

    - `-v ./:/app` maps the host folder to container folder to sync
    - `-p 8000:8000` maps port 8000 of the container to port 8000 on your local machine. Adjust the port numbers as needed.

5. **Access the application**

   Your application should now be running in the Docker container. You can access it by navigating to `http://localhost:8000` in your web browser.

### Additional Commands

- **Stop the container**

    ```sh
    docker stop mg-admin
    ```

- **Remove the container**

    ```sh
    docker rm mg-admin
    ```

- **Rebuild the image**

  If you make changes to the Dockerfile or dependencies, you may need to rebuild the image.

    ```sh
    docker build -t mg-admin .
    ```

### Troubleshooting

- **Check container logs**

  If something isn't working, you can check the container logs for errors.

    ```sh
    docker logs mg-admin
    ```

- **Shell access to the container**

  For debugging purposes, you may want to access the shell inside the running container.

    ```sh
    docker exec -it mg-admin /bin/sh
    ```

### Contributing

If you'd like to contribute to this project, please follow the [contribution guidelines](CONTRIBUTING.md).
