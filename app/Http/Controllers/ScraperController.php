<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\ScraperService;

class ScraperController extends Controller
{
	public function __construct()
	{
		$this->today             = Carbon::today()->format('Ymd'); 
		$this->punch_record_log  = $this->today.'-punch_record.log';
		$this->punch_record_json = $this->today.'-punch_record.json';
	}

	public function get_punch_record(){
		$ScraperService = new ScraperService;
		$punch_record   = $ScraperService->get_punch_record();
		return $punch_record;
	}

	public function save_punch_record_log(){
		$ScraperService = new ScraperService;
		$punch_record   = $ScraperService->get_punch_record();
		$contents       = str_replace("\n" , '' , $punch_record);
		Storage::disk('public')->put($this->punch_record_log, $contents);
	}

	public function save_punch_record_json(){
		$contents       = Storage::disk('public')->get($this->punch_record_log);
		/*分割每筆資料轉成陣列*/
		$contents       = str_replace("<html><table><tr><td>No.</td><td>用戶編號</td><td>用戶卡號</td><td>用戶工號/用戶姓名</td><td>日期</td><td>首筆時間</td><td>末筆時間</td></tr>","",$contents);
		$contents       = str_replace("</table>","",$contents);
		$contents       = str_replace("</body>","",$contents);
		$contents       = str_replace("</tr>","",$contents);
		$contents       = str_replace("</td>","",$contents);
		$contents_array = mb_split("<tr>",$contents);
		unset($contents_array[0]);

		/*每筆資料細項轉成陣列*/
		$tr_array = array();
		foreach ($contents_array as $key => $tr) {
			$td_array = mb_split("<td>",$tr);
			unset($td_array[0]);
			unset($td_array[1]);
			unset($td_array[3]);
			$td_array       = array_values($td_array);
			$tr_array[$key] = $td_array;
		}

		foreach ($tr_array as $key => $value) {
			if(is_string($value)) {
				$tr_array[$key] = urlencode($value);
			}
			else
			{
				foreach ($value as $key2 => $value2) {
					if(is_string($value2)) {
						$tr_array[$key][$key2] = urlencode($value2);
					}
				}
			}

		}

		$json = urldecode(json_encode($tr_array, JSON_PRETTY_PRINT));
		Storage::disk('public')->put($this->punch_record_json, $json);
	}



}