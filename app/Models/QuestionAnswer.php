<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questions;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $appends = ['question'];
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];
    public function getQuestionAttribute()
    {
        $findQuestions = Questions::where('id', $this->question_id)->first();

        return $findQuestions;
    }
}
