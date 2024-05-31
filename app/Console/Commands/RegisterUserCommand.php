<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class RegisterUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register';

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
        $name = text('What is the user\'s name?', required: true);
        $email = text('What is the user\'s email address?', validate: [
            'email' => 'required|email|unique:users,email',
        ]);
        $password = password('What is the user\'s password?', validate: [
            'password' => ['required',Password::default()],
        ]);
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->output->success('User created!');
    }
}
