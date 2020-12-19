<?php

declare(strict_types=1);

namespace Lifhold\Users\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements \Lifhold\Users\Contracts\User
{
    use SoftDeletes;

    protected $fillable = [
        "id", "email"
    ];

    protected $guarded = [
        "password"
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
