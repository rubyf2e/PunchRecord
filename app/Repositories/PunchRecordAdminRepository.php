<?php

namespace App\Repositories;

use App\Services\PunchRecordService;
use App\Contracts\PunchRecordInterface;
use Illuminate\Database\Eloquent\Collection;


class PunchRecordAdminRepository implements PunchRecordInterface
{

    private $PunchRecordService;
    public function __construct(PunchRecordService $PunchRecordService)
    {
        $this->PunchRecordService = $PunchRecordService;
    }

    public function punch_record_compile($member_no, $contents){
       return $this->PunchRecordService->punch_record_compile($member_no, $contents);
    }
}