<?php

namespace App\Helpers;
use Request;
use App\LogActivity as LogActivityModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class LogActivity
{
    public static function addToLog($subject)
    {
		$userId = Auth::user()->id;
		$namaUser = Auth::user()->nama;
		$satkerId = Auth::user()->satkerId;
			
    	$log = [];
    	$log['subject'] = $subject;
    	$log['userId'] = $userId;
    	$log['namaUser'] = $namaUser;
    	$log['satkerId'] = $satkerId;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
		LogActivityModel::create($log);
		
        return response()->json(compact('log'), 200);
    }

    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }
}