<?php

/**
 * @file
 * Model of quote_progresses table
 */

namespace Jauntin\SavingQuote\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * App\QuoteProgress
 *
 * @property string $id
 * @property string $email
 * @property array $data
 * @property DateTime $expire_at
 * @property DateTime|null $opened_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @method static Builder|QuoteProgress factory()
 * @method static Builder|QuoteProgress first()
 * @method static Builder|QuoteProgress newModelQuery()
 * @method static Builder|QuoteProgress newQuery()
 * @method static Builder|QuoteProgress query()
 * @method static Builder|QuoteProgress where($field, $value)
 * @method static Builder|QuoteProgress whereCreatedAt($value)
 * @method static Builder|QuoteProgress whereData($value)
 * @method static Builder|QuoteProgress whereEmail($value)
 * @method static Builder|QuoteProgress whereExpireAt($value)
 * @method static Builder|QuoteProgress whereId($value)
 * @method static Builder|QuoteProgress whereOpenedAt($value)
 * @method static Builder|QuoteProgress whereUpdatedAt($value)
 */
class QuoteProgress extends Model
{
    use HasUuids;

    protected $table = 'quote_progresses';

    protected $fillable = [
        'email',
        'data',
        'expire_at',
        'opened_at',
    ];

    /** @var array<string, string> $casts */
    protected $casts = [
        'data'      => 'array',
        'expire_at' => 'datetime',
        'opened_at' => 'datetime',
    ];
}
