# WordPress Deps Auto Updater - Checkout 

This is a simple GitHub Worklfow to checkout the PR the `boxuk/wp-deps-auto-update` action.

## Usage

You'll need to use the `boxuk/wp-checkout-deps-auto-update` action to setup your workflow if the event is triggered by `workflow_run`. We need the `worfklow_run_id` to pull the relevant PR number. 

```yml
# Other config...
on:
  pull_request: # For all PRs
  workflow_run: # For WP Updates
    workflows: ["Update WP Deps"]
    types:
      - completed

jobs:
  test:
    runs-on: ubuntu-latest
    name: Test
    steps:
      - name: Checkout
        uses: actions/checkout@v4
        if: github.event_name != 'workflow_run'

      - name: Checkout
        uses: boxuk/wp-checkout-deps-auto-update@main
        if: github.event_name == 'workflow_run'
        with:
          workflow_run_id: ${{ github.event.workflow_run.id }}
      
      # next steps...
```

## Contributing

Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!