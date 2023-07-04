<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		//Prompts on terminal
        $user['name'] = $this->ask('Name of the new user');
		$user['email'] = $this->ask('Email of the new user');
		$user['password'] = $this->secret('Password of the new user');
		
		$roleName = $this->choice('Email of the new user', ['admin', 'editor'], 1);
		
		//Check if the role is in the database
		$role = Role::where('name', $roleName)->first();
		if (!$role) {
			$this->error('Role not found');
			
			return -1;
		}
		
		//Validate the values to check for duplicates and etc.
		$validator = Validator::make($user, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', Password::defaults()]
		]);
		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $error) {
				$this->error($error);
			}
			
			return -1;
		}
		
		//Create the user using the $user and attach the role chosen
		DB::transaction(function () use ($user, $role) {
			$user['password'] = Hash::make($user['password']);
			$newUser = User::create($user);
			$newUser->roles()->attach($role->id);
		});
		
		$this->info('User '.$user['email'].' created successfully.');
		
		return 0;
    }
}
