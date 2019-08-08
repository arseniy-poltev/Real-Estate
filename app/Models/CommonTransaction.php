<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonTransaction extends Model
{
    protected $table = 'tbl_comm_transaction';
//    protected $primaryKey = 'ID';

    public static function getTransactionProjectList($projectName)
    {
        return self::where('Project Name', $projectName)->get();
    }

    public static function getIndicativeRental($projectName)
    {
        return self::where('Project Name', $projectName)->get();
    }
}
