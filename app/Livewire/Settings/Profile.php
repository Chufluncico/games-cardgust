<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public ?string $avatarSeed = null;


    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->avatarSeed = Auth::user()->avatar_seed;

    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);
        $user->avatar_seed = $this->avatarSeed;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    #[Computed]
    public function avatarUrl(): string
    {
        return "https://api.dicebear.com/9.x/thumbs/svg"
        . "?seed={$this->avatarSeed}"
        . "&backgroundColor=ffd5dc,ffdfbf,d1d4f9,b6e3f4,c0aede,fce7f3,e0f2fe,dcfce7,fef3c7,ede9fe,fad2e1,bee1e6,dfe7fd,e4c1f9,cdeac0,d9ed92,95d5b2,caf0f8,cdb4db,e9c46a"
        . "&shapeColor=0a5b83,69d2e7,ff8fab,ff6b6b,f6bd60,98c1d9,b8c0ff,c77dff,f94144,f3722c,43aa8b,577590,4361ee,7209b7,3a86ff,6d6875,344e41,2a9d8f,e76f51,06d6a0";
    }



    public function regenerateAvatar(): void
    {
        $this->avatarSeed = \Illuminate\Support\Str::random(12);
    }





}
