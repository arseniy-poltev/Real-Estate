<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandedTransaction extends Model
{
    protected $table = 'tbl_resi_transaction_landed';

    public static function getTransactionProjectList($projectName)
    {
        return self::where('Project Name', $projectName)->get();
    }
}
