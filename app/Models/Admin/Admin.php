<?php
namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements Auditable
{
    use HasRoles,Notifiable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $dontKeepAuditOf = ['remember_token'];
   protected $auditExclude = ['updated_at', 'created_at'];




}
