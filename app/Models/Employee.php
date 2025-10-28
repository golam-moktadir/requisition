<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'emp_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'date_of_birth',
        'joining_date',
        'emp_nid',
        'emp_birth_reg_no',            
        'present_address',
        'permanaunt_address',
        'emp_mobile_no',
        'last_edu_certificate',
        'emp_experiance_details',
        'emp_contact_person_name',
        'emp_contact_person_mobile',            
        'emp_remark',
        'emp_status', 
        'created_by',
        'updated_by',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
}
