# WordPress Deps Auto Updater - Checkout 

This is a simple GitHub Worklfow to mark the state of a status-check that's been triggered via the `workflow_run` trigger.

## Usage

You'll need to use the `boxuk/checkout-pr` action to setup your workflow if the event is triggered by `workflow_run`. We need the `worfklow_run_id` to pull the relevant PR number. 

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
        uses: boxuk/checkout-pr@main
        id: checkout-deps
        if: github.event_name == 'workflow_run'
      
      # Run Tests or whatever is needed...

      - name: Mark Check Outcome
        if: github.event_name == 'workflow_run'
        uses: boxuk/mark-check-status@main
        with: 
          status: ${{ job.status }}
          pr-head-sha: ${{ steps.checkout-deps.outputs.pr-head-sha }}
```

## Contributing

Please do not submit any Pull Requests here. They will be closed.
---

Please submit your PR here instead: https://github.com/boxuk/wp-packages

This repository is what we call a "subtree split": a read-only subset of that main repository.
We're looking forward to your PR there!