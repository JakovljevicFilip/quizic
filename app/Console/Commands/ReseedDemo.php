<?php

namespace Quizic\Console\Commands;

use Illuminate\Console\Command;

class ReseedDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and reseed the database for the demo environment';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return 0;
    }
}
