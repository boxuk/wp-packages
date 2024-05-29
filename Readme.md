# BoxUK WP Packages Mono Repo

This is the BoxUK mono-repo for our WordPress packages. The [WordPress Skeleton](https://github.com/boxuk/wp-project-skeleton) uses these packages to add functionality. 

## How to use

This mono repo allows you to develop any of the packages. To start a WordPress development environment to test against, just run `docker-compose up -d wordpress` and you'll be able to access WordPress at [http://localhost:8000](http://localhost:8000). 

We use [Monorepo Builder](https://github.com/symplify/monorepo-builder) tools to manage the mono-repo dependancies. 

## Structure

Each of the packages within the `packages` directory is an individual package. These should be standalone, and any dependancies they have (with another package or external) should be loaded via composer. 

## Adding Dependancies

Dependancies for each package should be defined in the package's `composer.json` file. Once the file has been altered, from the root of the project run the following commands: 

```sh
# Merge the dependancy tree
bin/composer run mono:merge 
# Install your package
bin/composer install <package-name>
```

## Adding a package

To create a package, you can run `bin/create-package <package-name>` and this will scaffold out all the necessary changes needed. In order for your package to be published, you'll also need to modify `/.github/workflows/packages.yml` to configure the package name and the target repository for publishing. This will automate pushing changes of the package out to the target repository, but you may need further work to ensure that repository is available via `composer` in your projects. 

## Tests

All packages need to have 100% test coverage. During CI they will be tested for this capability. 

## Javascript

If your package requires javascript, you can also setup a `package.json` file in the root of the package. Much like `composer.json`, this will be merged automatically at the root level. 

To run `npm` commands directly in your package run `bin/npm -w packages/<package-name>` with your command. For example `bin/npm -w packages/iconography run test` would run tests specifically in the iconography package. 

Commands can also be run globally across all packages using `turbo`. This is setup so that if you run `bin/npm run test` it will run test in every package that has a `package.json` file with a `test` script. You should try to keep naming consistent across packages to support this work. All currently supported scripts in `turbo` are listed in the `turbo.json` file at the root. 

During CI, the `lint`, `test` and `build` NPM scripts are run to validate the package quality. You should ensure your package supports these. 