<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsenceController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function absence(){
		return view('absence', [
			'title'            => '假單申請',
		]);
	}

}
