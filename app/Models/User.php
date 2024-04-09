<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    const USER_TYPE = [
        'ADMIN' => 1,
        'COMPANY' => 2,
        'STAFF' => 3,
        'USER' => 4,
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country_id',
        'state_id',
        'city_id',
        'contact_number',
        'password',
        'view_password',
        'profile_image',
        'user_type',
        'company_id',
        'status',
        'stripe_id',
        'paypal_id',
        'token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'view_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getUserStatusAttribute()
    {
        $status = $this->status;
        $string = 'Active';
        if ($status == 0) {
            $string = 'Deactive';
        }
        return $string;
    }

    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }

    public function state()
    {
        return $this->belongsTo(StateModel::class);
    }

    public function city()
    {
        return $this->belongsTo(CityModel::class);
    }
}
