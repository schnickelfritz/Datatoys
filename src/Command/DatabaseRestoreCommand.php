<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'app:database_restore', description: 'restores database data saved in a sql file')]
class DatabaseRestoreCommand extends Command
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

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::OPTIONAL, 'backup filename (without extension)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename') ?: $this->getLatestBackupFile();
        $filenameError = $this->checkFilename($filename);
        if ($filenameError !== null) {
            $io->error($filenameError);

            return Command::FAILURE;
        }
        $command = $this->assembleCommand($filename);
        if ($command === null) {
            $io->error('Missing required database URL components');

            return Command::FAILURE;
        }

        // 	symfony console doctrine:database:drop --if-exists -n --force
        //	symfony console doctrine:database:create -n
        //	symfony console doctrine:migrations:migrate -n

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

    private function assembleCommand(mixed $filename): ?string
    {
        if (!is_string($filename)) {
            return null;
        }
        $urlComponents = $this->urlComponents();
        if (empty($urlComponents)) {
            return null;
        }
        $prepareCommand = [
            'command' => ('dev' === $this->appEnv)
                ? 'docker exec -i ' . $this->databaseContainerName . ' mysql'
                : 'mysql',
            'user' => escapeshellarg($urlComponents['user']),
            'pass' => escapeshellarg($urlComponents['pass']),
            'host' => escapeshellarg($urlComponents['host']),
            'dbName' => escapeshellarg(ltrim($urlComponents['path'], '/')),
            'folderPath' => $this->backupsFolderPath,
            'filename' => $filename,
        ];

        return vsprintf('%s --user=%s --password=%s --host=%s %s < %s/%s',$prepareCommand);
    }

    private function getLatestBackupFile(): ?string
    {
        if (!is_dir($this->backupsFolderPath)) {
            return null;
        }
        $latestFile = null;
        $latestTime = 0;
        $files = scandir($this->backupsFolderPath);
        if ($files === false) {
            return null;
        }
        foreach ($files as $file) {
            $filePath = $this->backupsFolderPath . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                $fileModTime = filemtime($filePath); // Get file modification time
                if ($fileModTime > $latestTime) {
                    $latestTime = $fileModTime;
                    $latestFile = $file;
                }
            }
        }

        return $latestFile;
    }

    private function checkFilename(mixed $filename): ?string
    {
        if (!is_string($filename)) {
            return 'No backup files found';
        }
        $filePath = $this->backupsFolderPath . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($filePath)) {
            return 'File not found: ' . $filePath;
        }

        return null;
    }
}
