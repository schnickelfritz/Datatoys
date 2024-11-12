<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('app.version', '2024-10-15')
        ->set('app.title', 'Data-Toys')
        ->set('app.noreply_email', 'no-reply@hmmh.io')
        ->set('app.admin_email', 'martin.neumann@hmmh.de')
        ->set('app.database_url', '%env(resolve:DATABASE_URL)%')
        ->set('app.database_container_name', 'mysql_container_datatoys')
        ->set('app.env', '%env(APP_ENV)%')
        ->set('app.backups_folder_path', '%kernel.project_dir%/var/data-backups')
        ->set('app.gridfiles_folder_path', '%kernel.project_dir%/public/gridfiles')
    ;
};