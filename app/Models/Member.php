<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'member';
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function hobbies(){
        return $this->hasMany(Hobby::class,'user_id','id')->select('user_id','hoby');
    }
}
