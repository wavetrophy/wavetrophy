<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
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
            // shared directories
            ->sharedFilesAndDirs(['.env.local','public/media', 'config/jwt', 'config/fcm'])
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir($dir)
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl($git)
            // the repository branch to deploy
            ->repositoryBranch($branch)
            // the composer binary
            ->remoteComposerBinaryPath($composer)
            ->composerInstallFlags('--prefer-dist --no-interaction --no-dev --optimize-autoloader -vvv')
            // update the composer binary
            ->updateRemoteComposerBinary(false);
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    public function beforeUpdating()
    {
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        $this->log('Migrating database');
        $this->runRemote('{{ console_bin }} doctrine:migrations:migrate');
        $this->log("The deployment has finished.");
    }
};