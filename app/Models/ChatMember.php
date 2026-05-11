<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMember extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMemberFactory> */
    use HasFactory;
    protected $guarded = [];



    //TODO: Проверить это
    /*
    public function messages() : HasManyThrough
    {
        return $this->HasManyThrough(Message::class, User::class);
    }
    */

    public function chat() : BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
