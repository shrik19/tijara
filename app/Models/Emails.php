<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{
    protected $table 	= 'emails';
    protected $fillable = ['id','title','subject', 'contents', 'subject_en', 'contents_en', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','is_deleted', 'deleted_at' ];
}
