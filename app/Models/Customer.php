<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";
    protected $primaryKey = "cust_id";
    protected $fillable = [
        "first_name",
        "org_name",
        "org_address",
        "cust_mobile_no",
        "cust_email_address",
        "created_by",
        "updated_by",
    ];
}
