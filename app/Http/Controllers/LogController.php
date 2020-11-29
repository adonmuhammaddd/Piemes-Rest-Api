<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->roleId == 50)
        {
            $result = DB::table('log')->get();

            if ($result->count() == 0)
            {
                return response()->json(['message' => 'Tidak ada data'], 200); 
            }
            else
            {
                return response()->json(compact('result'), 200);
            }
            
        }
        if (Auth::user()->roleId != 50)
        {
            $result = LogActivity::query()
                ->join('users', 'log.idUser', '=', 'users.id')
                ->select('log.*')
                ->where('users.cabang', '=', Auth::user()->cabang)
                ->get();

            if ($result->count() == 0)
            {
                return response()->json(['message' => 'Tidak ada data'], 200); 
            }
            else
            {
                return response()->json(compact('result'), 200);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = LogActivity::find($id);
        $result->status = $request->status;
        $result->save();

        return response()->json([
            'message' => 'Berhasil update data',
            'result' => $result
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
