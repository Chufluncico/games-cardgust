<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_seed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    public function getAvatarUrlAttribute(): string
    {
        $seed = $this->avatar_seed ?? $this->name;

        return "https://api.dicebear.com/9.x/thumbs/svg"
        . "?seed={$seed}"
        . "&backgroundColor=ffd5dc,ffdfbf,d1d4f9,b6e3f4,c0aede,fce7f3,e0f2fe,dcfce7,fef3c7,ede9fe,fad2e1,bee1e6,dfe7fd,e4c1f9,cdeac0,d9ed92,95d5b2,caf0f8,cdb4db,e9c46a"
        . "&shapeColor=0a5b83,69d2e7,ff8fab,ff6b6b,f6bd60,98c1d9,b8c0ff,c77dff,f94144,f3722c,43aa8b,577590,4361ee,7209b7,3a86ff,6d6875,344e41,2a9d8f,e76f51,06d6a0";
    }





}
