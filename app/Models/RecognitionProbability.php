<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecognitionProbability extends Model
{
    protected $table = 'recognition_probabilities'; // Replace with your table name

    protected $fillable = [
        'probability',
    ];
}
