# WordPress Deps Auto Updater

This is a simple GitHub Worklfow to run the `update-packages` script from the given repository and create a PR with the output. 

## Usage

Create a `.github/workflows/wordpress-deps.yml` file with the following: 

```yml
name: Update WP Deps

on:
  schedule:
    - cron: '0 3 * * *'
  workflow_dispatch:

jobs:
  update-deps:
    runs-on: ubuntu-latest
    steps:
      # Setup
      - name: Checkout
        uses: actions/checkout@v2
        with:
          fetch-depth: 0
          persist-credentials: false
      - name: Run Update
        uses: 'boxuk/wp-deps-auto-update@main'
        with:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```


# Setting up your Project for WP Packages

WordPress bundles versions of certain dependancies from WordPress core, and when we use `wp-scripts` to compile our packages it strips out the versions we've defined in `package.json` and instead relies on the versions bundled by WordPress. This means that you should be using the same dependancy version as WordPress provides during development to make sure that you're building compatible code. 

Because of this, you should not allow `dependabot` or other automated solutions to update an `@wordpress/` packages in your node package. Instead, the `wp-scripts` `packages-update` command should be used. 

## Setting up your project

You should define a `WORDPRESS_VERSION` env value in your `.env` file. This should be the major and minor version ie, `6.6` or `7.2`. 

> You could use another technique to determine the WordPress version, such as reading from your `composer.json` file. 

Your `package.json` file should include a script which handles updating the the WP packages to the given version. If you're using
`turbo` to manage a mono-repo, this would typically look like: 
```jsonc package.json
{
    // ... rest of your package
    "scripts": {
        // ...any other scripts
        "packages-update": "turbo run packages-update --concurrency=1 -- -- --dist-tag=wp-$WORDPRESS_VERSION",
    }
}
```

Without `turbo` the command would look similar but without the concurrency and the extra `--` args. 

## Setting up Dependabot

You should disable dependabot from updating the included deps, by adding the following to your `dependabot.yml` file: 
```yml
    # NodeJS Deps
    - package-ecosystem: npm
      directory: "/"
      registries: "*"
      schedule:
          interval: daily
      ignore:
      # Ignore updates to the WordPress packages, we'll handle those manually.
        - dependency-name: "@wordpress/*"
        - dependency-name: "react"
        - dependency-name: "react-dom"
```

## Contributing

Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!