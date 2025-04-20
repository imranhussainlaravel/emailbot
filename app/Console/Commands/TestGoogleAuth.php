<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestGoogleAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-google-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $client = new Google\Client();
            $client->setAuthConfig(storage_path('app/google-credentials.json'));
            $client->addScope(Google\Service\Sheets::SPREADSHEETS_READONLY);
            
            $this->info("Authentication successful!");
            $this->info("Access token: ".json_encode($client->getAccessToken()));
        } catch (\Exception $e) {
            $this->error("Authentication failed: ".$e->getMessage());
        }
    }
}
