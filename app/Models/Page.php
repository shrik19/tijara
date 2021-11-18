<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table 	= 'pages';
    protected $fillable = ['id','title','slug', 'contents', 'title_en','slug_en', 'contents_en', 'display_in_section', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','is_deleted', 'deleted_at' ];
}
