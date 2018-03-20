<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Installation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure Feed Reader application.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->hello();
        $this->createEnvFiles();
        $this->generateAppKey();
        $this->linkStorage();
        $this->migrateDatabase();
        $this->installNpmPackages();
        $this->call('cache:clear');
        $this->goodbye();
    }

    /**
     * Creates necessary environment files.
     *
     * @return void
     */
    protected function createEnvFiles()
    {
        $files = [
            '.env.example' => '.env',
            '.env.testing.example' => '.env.testing',
        ];

        foreach ($files as $template => $target) {
            if (!file_exists($target)) {
                copy($template, $target);
                $this->line("✔ {$target} has been created successfully.");
            }
        }
    }

    /**
     * Generates application secret key.
     *
     * @return void
     */
    protected function generateAppKey()
    {
        if (strlen(config('app.key')) === 0) {
            $this->call('key:generate');
            $this->line("✔ Encryption key has been generated.");
        }
    }

    /**
     * Creates storage symbolic link.
     *
     * @return void
     */
    protected function linkStorage()
    {
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
            $this->line("✔ Storage symlink has been created.");
        }
    }

    /**
     * Migrates databases.
     *
     * @return void
     */
    protected function migrateDatabase()
    {
        $connectionMap = [
            'local' => ['mysql'],
            'testing' => ['mysql_test'],
            'both' => ['mysql', 'mysql_test'],
        ];

        $env = $this->choice(
            'Choose environment to migrate the database',
            ['local', 'testing', 'both'],
            0
        );

        foreach ($connectionMap[$env] as $connection) {
            if ($this->confirm("Migrate the database for '{$connection}' connection?", false)) {
                $this->call('migrate', ['--force' => true, '--database' => $connection]);
                $this->line("✔ Database has been migrated.");
            }
        }
    }

    /**
     * Installs NPM packages and compiles assets.
     *
     * @return void
     */
    protected function installNpmPackages()
    {
        $this->yarn();
        $this->mix();
    }

    /**
     * Runs 'yarn install' command.
     *
     * @return void
     */
    protected function yarn()
    {
        $this->runProcess(
            'yarn install --force --no-progress',
            "Do you want to run 'yarn install' command?",
            "✔ NPM packages have been installed."
        );
    }

    /**
     * Runs 'npm run dev' command.
     *
     * @return void
     */
    protected function mix()
    {
        $this->runProcess(
            'npm run dev',
            "Do you want to compile assets?",
            "✔ All assets have been compiled."
        );
    }

    /**
     * Creates new Symfony process to run a given command.
     *
     * @param  string|array $command
     * @param  string $question
     * @param  string $message
     * @return void
     */
    protected function runProcess($command, $question, $message)
    {
        if ($this->confirm($question, false)) {
            $process = new Process($command);
            $process->disableOutput();

            try {
                $process->mustRun();
                $this->line($message);
            } catch (ProcessFailedException $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @param  array $data
     * @return void
     */
    protected function updateEnvFile($data)
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($data as $key => $value) {
            file_put_contents($envFile, preg_replace(
                "/{$key}=(.*)/",
                "{$key}={$value}",
                file_get_contents($envFile)
            ));
        }
    }

    /**
     * Says hello.
     *
     * @return void
     */
    protected function hello()
    {
        $this->info('RSS Feed Reader application installation');
        $this->info('----------------------------------------');
    }

    /**
     * Says goodbye.
     *
     * @return void
     */
    protected function goodbye()
    {
        $this->info('✔ The installation process is complete.');
        $this->info('✔ You can access your application at 0.0.0.0:8000');
    }
}
