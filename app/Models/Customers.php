<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table 	= 'Customer';
    protected $fillable = ['Name','ContactName','Address','Town','County','Country','TelNumber','TelWork','FaxNo','Email','TaxRef','PostCode','SetupDate','Note','FreeComputer','IsSQLDB','IsAccessDB','IsDeleted'];
}
