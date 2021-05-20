<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    protected $table 	= 'sliders';
    protected $fillable = ['id','title','sliderImage','description','link','sequence_no','status','created_at','updated_at' ];
}
