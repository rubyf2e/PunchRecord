<?php
namespace App\Services;
use Goutte\Client;

class PunchRecordService
{
	public function punch_record_compile($member_no, $contents){
		$member_array = [];
        /*
        * 每筆資料細項轉成用員編分類
        * 用員編為key
        */

        $forget_punch_num = 0;
        if(is_array($contents)){
            if(count($contents) > 0){
               unset($contents[0]);
            	foreach ($contents as $key => $item) {
            		$deduction_time                              = array();
            		$no                                          = 'AA'.str_pad(($item[0]), 4, '0', STR_PAD_LEFT);
            		$date_array[$no][$key]['date']               = date("Y-m-d", strtotime($item[2]));
            		$date_array[$no][$key]['off']                = $item[4];
            		$date_array[$no][$key]['on']                 = $item[3];

            		$date_array[$no][$key]['off_date']           = $item[2].' '.$item[4];
            		$date_array[$no][$key]['on_date']            = $item[2].' '.$item[3];

            		$off_date_strtotime                          = strtotime($date_array[$no][$key]['off_date']);
            		$on_date_strtotime                           = strtotime($date_array[$no][$key]['on_date']);

            		$date_array[$no][$key]['off_date_strtotime'] = $off_date_strtotime;
            		$date_array[$no][$key]['on_date_strtotime']  = $on_date_strtotime;

            		$date_array[$no][$key]['lunch_time_start']   = $item[2].' '.'12:00:00';
            		$date_array[$no][$key]['lunch_time_end']     = $item[2].' '.'13:00:00';
            		$lunch_time_start                            = strtotime($date_array[$no][$key]['lunch_time_start']);
            		$lunch_time_end                              = strtotime($date_array[$no][$key]['lunch_time_end']);

            		/*判斷打卡型態*/
            		if($on_date_strtotime === false)
            		{
            			$am_type = array('type' => 'C', 'message' => '早上未打卡');
            			$forget_punch_num++;
            		}
            		elseif($on_date_strtotime > $lunch_time_start)
            		{
            			$am_type = array('type' => 'B', 'message' => '超過中午12點');
            		}
            		elseif($on_date_strtotime < $lunch_time_start)
            		{
            			$am_type = array('type' => 'A', 'message' => '正常');
            		}

            		if($off_date_strtotime === false)
            		{
            			$pm_type = array('type' => 'C', 'message' => '下班未打卡');
            			$forget_punch_num++;
            		}
            		elseif($off_date_strtotime < $lunch_time_start)
            		{
            			$pm_type = array('type' => 'B', 'message' => '中午12點之前離開');
            		}
            		elseif($off_date_strtotime > $lunch_time_start)
            		{
            			$pm_type = array('type' => 'A', 'message' => '正常');
            		}

            		$date_array[$no][$key]['am_type'] = $am_type;
            		$date_array[$no][$key]['pm_type'] = $pm_type;


            		/* 判斷 遲到*/
            		$late        = ($on_date_strtotime > strtotime($item[2].' '.'09:30:00'));

            		if($late === true)
            		{
            			$date_array[$no][$key]['late'] = array('type' => 'B', 'message' => '遲到');
            		}
            		else
            		{
            			$date_array[$no][$key]['late'] = array('type' => 'A', 'message' => '正常');
            		}

            		if($on_date_strtotime === false || $off_date_strtotime === false)
            		{
            			$date_array[$no][$key]['late']        = array('type' => 'C', 'message' => '打卡不正常');
            			$date_array[$no][$key]['leave_early'] = array('type' => 'C', 'message' => '打卡不正常');
            		}

            		/* 判斷 彈性時間 reset 上下班時間 上班低於9:00 下班大於18:00*/
            		$reset_on_date_strtotime  = strtotime($item[2].' '.'09:00:00');
            		$reset_off_date_strtotime = strtotime($item[2].' '.'18:00:00');

            		/*判斷 彈性時間範圍*/
            		$reset_flextime_strtotime = 60*30;


            		/*因上班時間 提早不算加班 因此如果小於早上9:00 皆設定為 早上 9:00*/
            		if($on_date_strtotime <= $reset_on_date_strtotime)
            		{
            			$on_date_strtotime = $reset_on_date_strtotime;
            		}           

            		/*reset 超過下班時間參數*/
            		$over_date_strtotime = 0;


            		/*判斷打卡型態*/
            		switch ($am_type['type']) {
            			case 'A':
            			switch ($pm_type['type']) {
            				/*超過中午12點下班*/
            				case 'A':
            				$origin_minute_strtotime = $off_date_strtotime - $on_date_strtotime;
            				$deduction_time          = array_merge($deduction_time, array(
            					'lunch_time_strtotime'   => 60*60,
            				));

            				$leave_early         = ($off_date_strtotime < ($on_date_strtotime + $deduction_time['lunch_time_strtotime'] + 60*60*8));
            				if($leave_early === false)
            				{
            					$over_date_strtotime = $off_date_strtotime - ($on_date_strtotime + $deduction_time['lunch_time_strtotime'] + 60*60*8);  
            				}

            				break;

            				/*中午12點之前離開*/
            				case 'B':
            				$origin_minute_strtotime = $off_date_strtotime - $on_date_strtotime;
            				$leave_early             = true;
            				break;

            				case 'C':
            				$origin_minute_strtotime = null;
            				$leave_early             = null;
            				break;
            			}

            			break;

            			/*超過中午12點才到*/
            			case 'B':
            			switch ($pm_type['type']) {
            				/*超過中午12點下班*/
            				case 'A':
            				$origin_minute_strtotime = $off_date_strtotime - $on_date_strtotime;

            				if($on_date_strtotime < $lunch_time_end)
            				{
            					/*超過中午12點到 又小於 13:00 直接設置為 13:00上班*/
            					$on_date_strtotime = $on_date_strtotime;
            				}


            				/*只判斷18:00:00 以前走皆為早退*/
            				$leave_early  = ($reset_off_date_strtotime > $off_date_strtotime);

            				if($leave_early === false)
            				{
            					/*只判斷18:30:00 以後為加班*/
            					$over_date_strtotime = $off_date_strtotime - ($reset_off_date_strtotime + $reset_flextime_strtotime);   
            					$over_date_strtotime = ($over_date_strtotime < 0) ? 0 : $over_date_strtotime;
            				}

            				break;

            				/*不存在*/
            				case 'B':
            				case 'C':
            				$origin_minute_strtotime = null;
            				$leave_early             = null;
            				break;
            			}

            			break;

            			case 'C':

            			switch ($pm_type['type']) {
            				case 'A':
            				case 'B':
            				case 'C':
            				$origin_minute_strtotime = null;
            				$leave_early             = null;
            				break;
            			}

            			break;
            		}

            		$this->leave_early($date_array, $leave_early, $no, $key);

            		$date_array[$no][$key]['origin_minute_strtotime'] = $origin_minute_strtotime;
            		$date_array[$no][$key]['origin_minute']           = ($origin_minute_strtotime === null) ? null : round($origin_minute_strtotime / 60);
            		$date_array[$no][$key]['origin_hour']             = ($origin_minute_strtotime === null) ? null : round($date_array[$no][$key]['origin_minute'] / 60, 2);


            		$real_minute_strtotime = $origin_minute_strtotime;

            		/*刪掉彈性時間及午餐時間*/
            		foreach ($deduction_time as $type => $time) {
            			$real_minute_strtotime -= $time;
            		}


            		$real_minute_strtotime                          = ($real_minute_strtotime < 0) ? 0 : $real_minute_strtotime;
            		$date_array[$no][$key]['real_minute_strtotime'] = $real_minute_strtotime;
            		$date_array[$no][$key]['real_minute']           = round($real_minute_strtotime / 60);
            		$date_array[$no][$key]['real_hour']             = round($date_array[$no][$key]['real_minute'] / 60, 2);

            		$date_array[$no][$key]['over_time']           = ($over_date_strtotime > 0) ? '加班' : '未加班';
            		$date_array[$no][$key]['over_date_strtotime'] = $over_date_strtotime;
            		$date_array[$no][$key]['over_date_minute']    = round($over_date_strtotime /60);
            		$date_array[$no][$key]['over_date_hour']      = round($over_date_strtotime /60/60, 2);
            		$date_array[$no][$key]['deduction_time']      = $deduction_time;    

            		$member_array[$no] = array(
            			'no'               => $no,
            			'name'             => $item[1],
            			'forget_punch_num' => $forget_punch_num,
            			'record'           => []
            		);
            	}


                /*
                *將日期匯入員編陣列
                */
                foreach ($contents as $key => $item) {
                	$no = 'AA'.str_pad(($item[0]), 4, '0', STR_PAD_LEFT);
                	array_push($member_array[$no]['record'], $date_array[$no][$key]);
                }
            }
        }

      return $member_array;
    }



    /* 判斷 早退*/
    private function leave_early(&$date_array, $leave_early, $no, $key){
    	switch ($leave_early) {
    		case true:
    		$date_array[$no][$key]['leave_early'] = array('type' => 'B', 'message' => '早退');
    		break;
    		case false:
    		$date_array[$no][$key]['leave_early'] = array('type' => 'A', 'message' => '正常');
    		break;

    		case null:
    		$date_array[$no][$key]['leave_early'] = array('type' => 'C', 'message' => '打卡不正常');
    		break;

    	}

    }

  }