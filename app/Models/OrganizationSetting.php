<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OrganizationSetting extends Model
{
    use HasFactory;    

    protected $table = 'organization_settings';

    protected $fillable = [
        'org_name',
        'org_address',
        'org_mobile',
        'org_email',
    ]; 

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }    
    
}
