<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;
use Symfony\Component\Dotenv\Dotenv;

return new class extends DefaultDeployer
{
    public function configure()
    {
        $dotenv = new Dotenv();
        $dotenv->overload(__DIR__ . '/../../.env.prod');
        $host = getenv('DEPLOY_HOST');
        $port = getenv('DEPLOY_PORT');
        $user = getenv('DEPLOY_USER');
        $dir = getenv('DEPLOY_DIR');
        $git = getenv('DEPLOY_GIT_URL');
        $branch = getenv('DEPLOY_GIT_BRANCH');
        $composer = getenv('DEPLOY_COMPOSER_BIN');

        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server("{$user}@{$host}:{$port}")
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir($dir)
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl($git)
            // the repository branch to deploy
            ->repositoryBranch($branch)
            // the composer binary
            ->remoteComposerBinaryPath($composer)
            ->composerInstallFlags('--prefer-dist --no-interaction --no-dev')
            // update the composer binary
            ->updateRemoteComposerBinary(true);
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    public function beforeUpdating()
    {
        // see https://github.com/EasyCorp/easy-deploy-bundle/issues/35
        // see https://github.com/EasyCorp/easy-deploy-bundle/blob/14edd418d82d2c616d79ecf2830c6140b0dc3971/src/Deployer/DefaultDeployer.php
        // $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }} 2>/dev/null');
        $this->runRemote('cp {{ deploy_dir }}/.env.local {{ project_dir }}/.env 2>/dev/null');
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        $this->log('Migrating database');
        $this->runRemote('{{ console_bin }} doctrine:migrations:migrate');
        $this->runLocal('say "The deployment has finished."');
    }
};
