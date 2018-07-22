<?php
namespace App\Contracts;

interface PunchRecordInterface
{
    /**
     * 傳回打卡記錄
     *
     * @return Collection
     */
    public function punch_record_compile($member_no, $contents);
}