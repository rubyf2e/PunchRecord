<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\User;
use App\Contracts\PunchRecordInterface;

class PunchRecordController extends BaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(Request $request)
    {
    	parent::__construct();
    	$this->today                  = Carbon::today()->format('Ymd'); 
    	$this->punch_record_file      = $this->today.'-punch_record.log';
    	$this->punch_record_json_file = $this->today.'-punch_record.json';
    	$this->PunchRecordInterface   = parent::bind_Interface(parent::globalArray()['login_type'], 'PunchRecord');
    	$this->request                = $request;

    	if($request->session()->exists('punch_record_json') === false)
    	{
    		$punch_record_json = '';
    		if(Storage::disk('public')->has($this->punch_record_json_file)){
    			$punch_record_json       = Storage::disk('public')->get($this->punch_record_json_file);
    			$punch_record_json       = json_decode($punch_record_json, true);

    		}

    		$request->session()->put('punch_record_json', $punch_record_json);
    		$this->punch_record_json = $punch_record_json;
    	}
    	else
    	{
    		$this->punch_record_json = $request->session()->get('punch_record_json');
    	}
    }


		/*
		* 用中午12點當分割點
		* 彈性上班 9:00~9:30
		* 彈性下班 18:00~18:30
		* 
		* 
		*/

		public function index($member_no = '')
		{
			if($member_no === '')
			{
				$member_no   = Auth::user()->member_no;	
				$member_name = Auth::user()->name;
			}
			elseif(session('login_type') === 'Admin')
			{
				$member_name = User::select('name')->where('member_no', $member_no)->get()[0]->name;	
			}
			else
			{
				$member_name = Auth::user()->name;
			}


			$data        = $this->punch_record($member_no);
			$table_title = array(
				'日期',
				'上班時間',
				'下班時間',
				'上午型態',
				'下午型態',
				'遲到',
				'早退',
				'統計分數',
				'統計時數',
				'實際分數',
				'實際時數',
				'可申請加班分數',
				'可申請加班時數',
			);

			$record           = (isset($data['record'])) ? $data['record'] : [];
			$forget_punch_num = (isset($data['forget_punch_num'])) ? $data['forget_punch_num'] : 0;
			return view('punch_record', [
				'title'            => '打卡記錄-'.$member_name,
				'data'             => $record,
				'table_title'      => $table_title,
				'forget_punch_num' => $forget_punch_num
			]);
		}


		public function punch_record_download()
		{
			return response()->download(Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix().'punch_record.xls');
		}


		private function punch_record($member_no = ''){

			if($this->request->session()->exists('punch_record_array') === false)
			{
				$punch_record_array = $this->PunchRecordInterface->punch_record_compile($member_no, $this->punch_record_json);
				$this->request->session()->put('punch_record_array', $punch_record_array);
			}
			else
			{
				$punch_record_array = $this->request->session()->get('punch_record_array');
			}


			if(array_key_exists($member_no, $punch_record_array))
			{
				return $punch_record_array[$member_no];
			}
			else
			{
				return $punch_record_array;
			}
		}

	}
