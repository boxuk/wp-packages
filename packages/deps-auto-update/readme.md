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

env: 
  WORDPRESS_VERSION: ${{ vars.WORDPRESS_VERSION }}

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
          WP_VERSION: ${{ env.WORDPRESS_VERSION }}
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

You could use another technique to determine the WordPress version, such as reading from your `composer.json` file. 

## Contributing

Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!