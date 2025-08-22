<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;


class DiscountCode extends Model
{
    use HasFactory, QueryScope;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'start_date',
        'end_date',
        'publish',
        'max_usage',
        'used_count',
        'is_unlimited',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'discount_code_user');
    }

    /**
     * Kiểm tra mã giảm giá có thể sử dụng không
     */
    public function canBeUsed(): bool
    {
        // Kiểm tra trạng thái
        if (!$this->publish) {
            return false;
        }

        // Kiểm tra hết hạn
        if (checkExpiredDate($this->end_date)) {
            return false;
        }

        // Kiểm tra lượt sử dụng
        if (!$this->is_unlimited && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * Tăng số lượt sử dụng
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Kiểm tra còn lượt sử dụng không
     */
    public function hasRemainingUsage(): bool
    {
        return $this->is_unlimited || $this->used_count < $this->max_usage;
    }
}
