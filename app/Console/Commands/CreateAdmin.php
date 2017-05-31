<?php

namespace Brightfox\Console\Commands;

use Illuminate\Console\Command;
use Brightfox\User;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('Name of Administrator:');
        $email = $this->ask('Please add an email:');
        $password = $this->secret('Password:');

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)            
        ]);

        $admin->assignRole('admin');

        $this->info('Admin created successfully');
    }
}
