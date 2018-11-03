<?php 
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $fillable = ['to','user','message'];
}