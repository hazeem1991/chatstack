<?php 
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $fillable = ['method','endpoint','request','response','user'];
}