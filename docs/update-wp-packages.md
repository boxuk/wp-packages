# Updating WP Packages

WordPress bundles versions of certain dependancies from WordPress core, and when we use `wp-scripts` to compile our packages it strips out the versions we've defined in `package.json` and instead relies on the versions bundled by WordPress. This means that you should be using the same dependancy version as WordPress provides during development to make sure that you're building compatible code. 

Because of this, you should not allow `dependabot` or other automated solutions to update an `@wordpress/` packages in your node package. Instead, the `wp-scripts` `packages-update` command should be used. 

## Setting up your project

You should define a `WORDPRESS_VERSION` env value in your `.env` file. This should be the major and minor version ie, `6.6` or `7.2`. 
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

## Setting up workflow/automation

We have a script provided which can automate updating the WP Packages as the WP core version updates. 

You can add a file to your `.github/workflows` directory which automates updates. You will need to define the `WORDPRESS_VERSION` in the GitHub repo. You will likely require administrator access to the repo to do this, so raise to a senior/principal dev if you cannot do this. 

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
      - name: Run Update
        uses: './.github/wp-packages'
        with:
          WP_VERSION: ${{ env.WORDPRESS_VERSION }}
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}        
```

Broadly this script sets up node using an `.nvmrc` file, then runs `npm run packages-update`. If there's changes, it creates or updates a PR as appropriate. 

After the PR is created, you may need to manually trigger any test-suites you have. Any PRs created by automations do not typically trigger tests/workflows to run (to prevent recursion), so manually triggering is required. You can add a step to the workflow above to trigger. 
```yml
      - name: Run Tests
        uses: actions/github-script@v3
        with:
          github-token: ${{ secrets.GITHUB_TOKEN }}
          script: |
            await github.actions.createWorkflowDispatch({
              owner: context.repo.owner,
              repo: context.repo.repo,
              workflow_file: 'test-php.yml',
              ref: deps/wp-packages/wp-${{ env.WORDPRESS_VERSION }}
            });
            await github.actions.createWorkflowDispatch({
              owner: context.repo.owner,
              repo: context.repo.repo,
              workflow_file: 'test-js.yml',
              ref: deps/wp-packages/wp-${{ env.WORDPRESS_VERSION }}
            });
```
