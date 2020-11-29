<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use JWTAUth;

class TestingController extends Controller
{
    public function testPost(Request $request)
    {
        $satker = $request->only('satkerId');
        $satkerIds = [];
        for ($i = 0; $i < count($satker); $i++)
        {
            return response()->json([
                'message' => 'Get Data',
                'result' => $satker['satkerId']
            ], 200);
        }
        // return response()->json([
        //     'message' => 'Get Data',
        //     'result' => $request[$i]->satkerId
        // ], 200);
    }

    public function testGet(Request $request)
    {
        
        // $tglTerimaDokumenTemuan = $request->tglTerimaDokumenTemuan;
        // $namaInstansi = $request->namaInstansi;
        // $noLHA = $request->noLHA;
        // $tglLHA = $request->tglLHA;

        // $email = DB::table('tbl_user')
        //     ->select('email')
        //     ->where('satkerId', $result)
        //     ->get();
    
        return response()->json([
            'message' => 'Get Data',
            'result' => $request->all()
        ], 200);
    }
}
