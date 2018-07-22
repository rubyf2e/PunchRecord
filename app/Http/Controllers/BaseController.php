<?php

namespace App\Http\Controllers;
use App;
use View;
use Carbon\Carbon;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    protected $user;

    public function __construct()
    {
    	view()->composer('*', function($view){
    		$global = $this->globalArray();
    		session($global);
    		foreach ($global as $key => $value) {
    			View::share($key, $value);
    		}
    	});

    }

    public function globalArray(){
    		$permission = Auth::user()->permission;
    		$all_user   = [];

    		switch ($permission) {
    			case 'Admin':
    			$all_user = User::orderBy('member_no')->get();
    			break;
    			case 'Guest':
    			$permission = 'Guest';
    			break;
    			default:
    			break;
    		}

    		$global = array(
    			'forget_punch_num'  => 0,
    			'login_type'        => $permission,
    			'all_user'          => $all_user,
    		);

    	return $global;
    }

    public function bind_Interface($login_type, $model){
    	App::singleton("App\Contracts\\{$model}Interface","App\Repositories\\{$model}{$login_type}Repository");
    	return App::make("App\Contracts\\{$model}Interface");
    }


  }
