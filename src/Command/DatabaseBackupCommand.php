<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;
use function Symfony\Component\Clock\now;

#[AsCommand(name: 'app:database_backup', description: 'creates a sql file with current database data')]
class DatabaseBackupCommand extends Command
{
    public function __construct(
        #[Autowire('%app.database_url%')]
        private readonly string $databaseUrl,
        #[Autowire('%app.env%')]
        private readonly string $appEnv,
        #[Autowire('%app.database_container_name%')]
        private readonly string $databaseContainerName,
        #[Autowire('%app.backups_folder_path%')]
        private readonly string $backupsFolderPath,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $command = $this->assembleCommand();
        if ($command === null) {
            $io->error('Missing required database URL components');

            return Command::FAILURE;
        }
        $this->createBackupsFolder();
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $io->error($command);

            return Command::FAILURE;
        }
        $io->success($command);

        return Command::SUCCESS;
    }

    /**
     * @return array<string, string>
     */
    private function urlComponents(): array
    {
        $requiredKeys = ['user', 'pass', 'host', 'path'];
        $urlComponents = parse_url($this->databaseUrl);
        if (!is_array($urlComponents)) {
            return [];
        }
        $r = [];
        foreach ($requiredKeys as $key) {
            if (!isset($urlComponents[$key])) {
                return [];
            }
            $r[$key] = $urlComponents[$key];
        }

        return $r;
    }

    private function assembleCommand(): ?string
    {
        $urlComponents = $this->urlComponents();
        if (empty($urlComponents)) {
            return null;
        }
        $dbName = escapeshellarg(ltrim($urlComponents['path'], '/'));
        $prepareCommand = [
            'command' => ('dev' === $this->appEnv)
                ? 'docker exec ' . $this->databaseContainerName . ' mysqldump'
                : 'mysqldump',
            'user' => escapeshellarg($urlComponents['user']),
            'pass' => escapeshellarg($urlComponents['pass']),
            'host' => escapeshellarg($urlComponents['host']),
            'ignoreTable' => escapeshellarg($dbName . '.doctrine_migration_versions'),
            'dbName' => $dbName,
            'folderPath' => $this->backupsFolderPath,
            'filename' => now()->format('Y-m-d--H-i-s'),
        ];

        return vsprintf(
            '%s --user=%s --password=%s --host=%s --ignore-table=%s --no-create-info --skip-comments %s > %s/%s.sql',
            $prepareCommand
        );
    }

    private function createBackupsFolder(): void
    {
        $filesystem = new FileSystem();
        if (!$filesystem->exists($this->backupsFolderPath)) {
            $filesystem->mkdir($this->backupsFolderPath);
        }
    }
}
