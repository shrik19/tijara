<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table 	= 'pages';
    protected $fillable = ['id','title','slug', 'contents', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','is_deleted', 'deleted_at' ];
}
