<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    public const TYPE_PERCENT = 'percent';
    public const TYPE_NOMINAL = 'nominal';

    public const STATUSES = ['Aktif', 'Terjadwal', 'Kedaluwarsa', 'Nonaktif'];

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'type',
        'value',
        'start_date',
        'end_date',
        'quota',
        'used_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value'      => 'integer',
            'quota'      => 'integer',
            'used_count' => 'integer',
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_active'  => 'boolean',
        ];
    }

    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        $term = trim((string) $term);

        return $term === '' ? $q : $q->where('code', 'like', '%' . strtoupper($term) . '%');
    }

    public function scopeStatus(Builder $q, ?string $status): Builder
    {
        $today = Carbon::today()->toDateString();

        return match ($status) {
            'Aktif'       => $q->where('is_active', true)->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today),
            'Terjadwal'   => $q->where('is_active', true)->whereDate('start_date', '>', $today),
            'Kedaluwarsa' => $q->where('is_active', true)->whereDate('end_date', '<', $today),
            'Nonaktif'    => $q->where('is_active', false),
            default       => $q,
        };
    }

    public function getStatusAttribute(): string
    {
        $today = Carbon::today();

        return match (true) {
            ! $this->is_active                  => 'Nonaktif',
            $today->lt($this->start_date)       => 'Terjadwal',
            $today->gt($this->end_date)         => 'Kedaluwarsa',
            default                             => 'Aktif',
        };
    }

    public function getStatusColorAttribute(): array
    {
        return [
            'Aktif'       => ['#DCFCE7', '#047857', '#10B981'],
            'Terjadwal'   => ['#E0ECF6', '#346284', '#3F749C'],
            'Kedaluwarsa' => ['#EEF4F9', '#475569', '#64748B'],
            'Nonaktif'    => ['#FEE2E2', '#B91C1C', '#EF4444'],
        ][$this->status];
    }

    public function getValueLabelAttribute(): string
    {
        return $this->type === self::TYPE_PERCENT
            ? $this->value . '%'
            : 'Rp' . number_format($this->value, 0, ',', '.');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_PERCENT ? 'Persen' : 'Nominal';
    }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->quota - $this->used_count);
    }

    public function getUsagePercentAttribute(): int
    {
        return $this->quota > 0 ? min(100, (int) round($this->used_count / $this->quota * 100)) : 0;
    }

    public function getPeriodLabelAttribute(): string
    {
        $m = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $s = $this->start_date;
        $e = $this->end_date;

        if ($s->year !== $e->year) {
            return "{$s->day} {$m[$s->month]} {$s->year} – {$e->day} {$m[$e->month]} {$e->year}";
        }
        if ($s->month === $e->month) {
            return "{$s->day} – {$e->day} {$m[$e->month]} {$e->year}";
        }

        return "{$s->day} {$m[$s->month]} – {$e->day} {$m[$e->month]} {$e->year}";
    }

    public static function findUsable(string $code): ?self
    {
        $voucher = static::where('code', strtoupper(trim($code)))->first();

        return $voucher && $voucher->isUsable() ? $voucher : null;
    }

    public function isUsable(): bool
    {
        return $this->status === 'Aktif' && $this->remaining > 0;
    }

    public function discountFor(int $amount): int
    {
        $discount = $this->type === self::TYPE_PERCENT
            ? (int) floor($amount * $this->value / 100)
            : $this->value;

        return max(0, min($amount, $discount));
    }

    public function redeem(): bool
    {
        $today = Carbon::today()->toDateString();

        $affected = static::whereKey($this->getKey())
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->whereColumn('used_count', '<', 'quota')
            ->increment('used_count');

        if ($affected) {
            $this->refresh();
        }

        return (bool) $affected;
    }

    public static function suggestCodes(string $code, int $limit = 3): array
    {
        $code = strtoupper(trim($code));
        $base = rtrim(preg_replace('/\d+$/', '', $code), '-_') ?: $code;
        $months = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];

        $candidates = [$base . '15', $base . '20', $base . '25', $base . $months[now()->month - 1], $base . now()->year];
        $candidates = array_values(array_filter(
            array_unique($candidates),
            fn ($c) => $c !== $code && strlen($c) <= 30
        ));
        $taken = static::whereIn('code', $candidates)->pluck('code')->all();

        return array_slice(array_values(array_diff($candidates, $taken)), 0, $limit);
    }
}
