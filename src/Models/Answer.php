<?php

namespace Code16\Formoj\Models;

use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = "formoj_answers";

    protected $guarded = ["id"];

    /** @var array */
    protected $casts = [
        'content' => 'json',
    ];

    protected $dates = [
        "created_at", "updated_at",
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return AnswerFactory::new();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @param string $attribute
     * @return mixed|null
     */
    public function content($attribute)
    {
        return $this->content[$attribute] ?? null;
    }
}