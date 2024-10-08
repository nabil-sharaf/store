<?php
namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasRoles,Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
