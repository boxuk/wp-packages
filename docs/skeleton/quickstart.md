# Quickstart

#### Pre-requisites
##### Pre-requisites for Windows systems
* [WSL](https://docs.microsoft.com/en-us/windows/wsl/install)
* [Linux Kernel Update Package](https://docs.microsoft.com/en-gb/windows/wsl/install-manual#step-4---download-the-linux-kernel-update-package)
* [Cygwin](https://cygwin.com/install.html) (be sure to include the gettext package on install)
##### Pre-requisites for all systems
* [Docker](https://www.docker.com/)
* [Docker compose](https://docs.docker.com/compose/install/)

## TL;DR

If you just want a ready to go environment you can just use the following commands. 

```
bin/install [project_name] [docker_network_name] [php_version]

# project_name will default to boxuk-wp-skeleton if not provided.
# docker_network_name will default to boxuk-docker
# php_version allows you set which PHP version you wish to run, e.g. 8.2, 8.3.
```

> The docker network is required to ensure the loopback works with the expected IP address.

> Note: This will start the containers in detached mode, use `docker-compose stop` if you wish to stop them.

If you're after more detail read the [docker setup](docker-setup.md) or [non docker setup](non-docker-setup.md) docs instead.
