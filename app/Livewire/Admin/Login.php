<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.login')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'user_type' => 1,
        ], $this->remember)) {
            session()->regenerate();

            $this->redirectRoute('admin.dashboard');

            return;
        }

        $this->reset('password');
        $this->addError('credentials', 'Invalid credentials or not an admin account.');
    }

    public function render()
    {
        return view('livewire.admin.login')->title('Superadmin Login');
    }
}
