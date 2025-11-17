<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@gagaleri.com} {--password=admin123} {--name=Admin GaGaleri}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the gallery system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        try {
            // Try to create user with explicit ID handling
            DB::statement('SET SESSION sql_mode = ""');
            
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->save();

            $this->info('Admin user created successfully!');
            $this->info("Email: {$email}");
            $this->info("Password: {$password}");
            $this->info('You can now login to the admin panel at /admin/login');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            $this->info('Trying alternative method...');
            
            // Alternative method using raw SQL
            try {
                DB::insert('INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)', [
                    $name,
                    $email,
                    Hash::make($password),
                    now(),
                    now(),
                    now()
                ]);
                
                $this->info('Admin user created successfully using alternative method!');
                $this->info("Email: {$email}");
                $this->info("Password: {$password}");
                $this->info('You can now login to the admin panel at /admin/login');
                
                return 0;
            } catch (\Exception $e2) {
                $this->error('Failed to create admin user with alternative method: ' . $e2->getMessage());
                $this->info('Please check your database configuration and ensure the users table exists.');
                return 1;
            }
        }
    }
}
