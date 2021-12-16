name: MSPipeline

on:
  push:
    branches:
      - dev
  pull_request:
    branches: 
      - dev

jobs:
    build:
        name: Azure Pipeline Start
        runs-on: ubuntu-latest
        steps:
        - name: Portal Test Repo Pipeline
          uses: Azure/pipelines@v1
          with:
            azure-devops-project-url: https://dev.azure.com/seregazolotaryow64/InvestportalAplex
            azure-pipeline-name: 'Portal Test'
            azure-devops-token: ${{ secrets.AZURE_DEVOPS_TOKEN }}
	    