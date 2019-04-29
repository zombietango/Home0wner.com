# Home0wner.com
A purposely insecure web application designed for training developers and security professionals on Application Security Testing methodologies.

## Installation

Home0wner.com relies on Docker and Docker Compose to run. Everything is self-contained within the container platform and no additional installation is required beyond just running Docker Compose within the Home0wner.com environment.

To use this project, you will need a working install of Git, Docker, and Docker Compose. If you don't already have these, look in Software Center or use the links below:

- Docker: https://docs.docker.com/install/
- Docker Compose: https://docs.docker.com/compose/install/
- Git: https://git-scm.com/downloads

IT IS VERY IMPORTANT THAT THIS CONTAINER NOT BE RUN IN PRODUCTION!! This application purposely contains multiple security vulnerabilities. Exposing it to other computer networks, especially those running production systems, will result in serious exposure of critial systems and data. The application server binds to localhost by default. DO NOT MODIFY THIS! 

### Container Initialization

Once your local desktop environment is ready, clone this repository. Change to the Docker directory and run Docker-compose.

#### Mac OS
`cd {PATH TO CLONED REPO}/Docker`

`docker-compose up --detach --build`

#### Windows
`cd {PATH TO CLONED REPO}\Docker`

`docker-compose up --detach --build`

## Usage

Once the Docker container is built and running, simply point your browser at:

http://localhost:8880/index.html

That's it! 

## Bugs and Missing Features

Home0wner.com is currently in very late alpha. While most of the core features of the app are working, there are numerous UI bugs, missing features, template text remaining, etc. We are working to get rid of a lot of this, as well as adding in new features and vulnerabilities, so make sure to keep the repo up-to-date. Below will be a tracking list of all known bugs and features. Please feel free to use the Git Issues tracker to submit bugs or feature requests.