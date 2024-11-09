<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Process\Process;
use function Symfony\Component\Clock\now;

#[AsCommand(name: 'app:database_backup', description: 'creates a sql file with current database data')]
class DatabaseBackupCommand extends Command
{
    public function __construct(
        #[Autowire('%app.database_url%')]
        private string $databaseUrl,
        #[Autowire('%app.env%')]
        private string $appEnv,
        #[Autowire('%app.database_container_name%')]
        private string $databaseContainerName,
        #[Autowire('%app.backups_directory%')]
        private string $backupsDirectory,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //create backupsDirectory if not exists

        $urlComponents = parse_url($this->databaseUrl);
        $dbName = ltrim($urlComponents['path'] ?? '', '/');
        $command = ('dev' === $this->appEnv)
            ? 'docker exec ' . $this->databaseContainerName . ' mysqldump'
            : 'mysqldump';

        $timestamp = now()->format('Y-m-d--H-i-s');

        $commandWithParams = sprintf(
            '%s --user=%s --password=%s --host=%s --ignore-table=%s --no-create-info %s > %s/%s.sql',
            $command,
            escapeshellarg($urlComponents['user'] ?? ''),
            escapeshellarg($urlComponents['pass'] ?? ''),
            escapeshellarg($urlComponents['host'] ?? ''),
            escapeshellarg($dbName . '.doctrine_migration_versions'),
            escapeshellarg($dbName),
            $this->backupsDirectory,
            $timestamp,
        );

        $process = Process::fromShellCommandline($commandWithParams);
        $process->run();
        if (!$process->isSuccessful()) {
            $io->error($commandWithParams);

            return Command::FAILURE;
        }

        $io->success($commandWithParams);

        return Command::SUCCESS;
    }
}
