<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kpa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KPAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $satkerUser = Auth::user()->satkerId;
        if (Auth::user()->roleId == 50)
        {
            $result = DB::table('tbl_kpa')
                ->where('isDeleted', 0)
                ->get();
        }
        else
        {
            $result = DB::table('tbl_kpa')
                ->where('isDeleted', 0)
                ->where('satkerId', Auth::user()->satkerId)
                ->get();
        }

        return response()->json(compact('result'), 200);
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
        $validator = Validator::make($request->all(), [
            'satkerId' => 'required|numeric|max:255',
            'namaKpa' => 'required|string|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $satkerInput = $request->get('satkerId');
        $namaKpaInput = $request->get('namaKpa');

        $kpa = Kpa::query()
            ->where('namaKpa', $namaKpaInput)
            ->where('satkerId', $satkerInput)
            ->count();

        if ($kpa > 0)
        {
            return response()->json([
                'message' => 'Data sudah ada',
                'result' => $namaKpaInput.' dengan ID Satker '.$satkerInput
            ], 400);
        }
        else
        {
            // \LogActivity::addToLog(Auth::user()->nama.' telah menambahkan data Kpa '.$namaKpaInput);

            $result = Kpa::create([
                'satkerId' => $satkerInput,
                'namaKpa' => $namaKpaInput
            ]);

            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'result' => $result
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Kpa::where('id', $id)->get();

        return response()->json(compact('result'), 200);
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
        $result = [
            'satkerId' => $request->satkerId,
            'namaKpa' => $request->namaKpa
        ];

        $update = Kpa::where('id', $id)->update($result);

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah mengubah data KPA '. $request->namaKpa);
        
        return response()->json(compact('result'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Kpa::find($id);
        $result->isDeleted = '1';
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data KPA '. $result->namaKpa);
        
        return response()->json(compact('result'), 200);
    }
}