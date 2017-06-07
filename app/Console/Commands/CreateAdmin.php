<?php

namespace Brightfox\Console\Commands;

use Illuminate\Console\Command;
use Brightfox\User, Brightfox\UserDetail;

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

        $name = explode(" ", $name);
        if(count($name) == 1){
            $name[1] = "";
        }

        $admin = User::create([
            'name' => $name[0],
            'last_name' => $name[1],
            'email' => $email,
            'password' => bcrypt($password)            
        ]);

        $admin->assignRole('admin');

        $user_details = UserDetail::create([
            'photo' => null,
            'secondary_email' => null,
            'phone' => null,
            'mobile_phone' => null,
            'location_id' => null,
            'user_id' => $admin->id
        ]);

        $this->info('Admin created successfully');
    }
}
