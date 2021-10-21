<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table 	= 'settings';
    protected $fillable = ['id','site_title','footer_address','header_logo','footer_logo','copyright_content'];
}
