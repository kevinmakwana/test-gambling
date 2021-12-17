<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use \ForceUTF8\Encoding;

class Affiliate extends Model
{
    use HasFactory;

    public static function getAffiliates(){

        $data = Storage::disk('public')->get('affiliates.txt');
        
        $data = Encoding::toUTF8($data);
        
        return preg_split("/\r?\n|\r/", $data);
    }
}
