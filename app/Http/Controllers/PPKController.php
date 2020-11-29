<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ppk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PPKController extends Controller
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
            $result = DB::table('tbl_ppk')
                ->join('tbl_satker', 'tbl_ppk.satkerId', '=', 'tbl_satker.id')
                ->select('tbl_ppk.*', 'tbl_satker.namaSatker')
                ->where('tbl_ppk.isDeleted', 0)
                ->get();

            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = DB::table('tbl_ppk')
                ->join('tbl_satker', 'tbl_ppk.satkerId', '=', 'tbl_satker.id')
                ->select('tbl_ppk.*', 'tbl_satker.namaSatker')
                ->where('tbl_ppk.isDeleted', 0)
                ->where('tbl_ppk.satkerId', Auth::user()->satkerId)
                ->get();
            
            $dataSatker = DB::table('tbl_satker')
                ->select('*')
                ->where('id', Auth::user()->satkerId)
                ->first();    

            return response()->json(compact(['result', 'dataSatker']), 200);
        }

    }
    
    public function show($id)
    {
        $result = DB::table('tbl_ppk')
            ->join('tbl_satker', 'tbl_ppk.satkerId', '=', 'tbl_satker.id')
            ->select('tbl_ppk.*', 'tbl_satker.namaSatker')
            ->where('tbl_satker.id', $id)
            ->get();

        return response()->json(compact('result'), 200);
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
            'namaPpk' => 'required|string|max:255',
            'satkerId' => 'required|numeric|max:255'
        ]);

        $namaPpk = $request->namaPpk;
        $satkerId = $request->satkerId;

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $ppk = Ppk::query()
            ->where('namaPpk', $namaPpk)
            ->where('satkerId', $satkerId)
            ->count();

        if ($ppk > 0)
        {
            return response()->json([
                'message' => 'Data sudah ada',
                'result' => $namaPpk.' dengan ID Satker '.$satkerId
            ], 400);
        }
        else
        {
            // \LogActivity::addToLog(Auth::user()->nama.' telah menambahkan data Kpa '.$namaKpaInput);

            $insert = [
                'namaPpk' => $namaPpk,
                'satkerId' => $satkerId
            ];

            Ppk::create($insert);
            $ppkId = DB::table('tbl_ppk')
                ->latest('created_at')
                ->first();
            $satkerQuery = DB::table('tbl_satker')
                ->where('id', $satkerId)
                ->first();

            $namaSatker = $satkerQuery->namaSatker;
            
            $result = [
                'id' => $ppkId->id,
                'namaPpk' => $namaPpk,
                'satkerId' => $satkerId,
                'namaSatker' => $namaSatker
            ];

            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'result' => $result
            ], 200);
        }
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
        $ppk = Ppk::query()
            ->where('namaPpk', $request->namaPpk)
            ->where('satkerId', $request->satkerId);

        if ($ppk->count() > 0)
        {
            return response()->json([
                'message' => 'Data sudah ada',
                'result' => $namaPpk.' dengan ID Satker '.$satkerId
            ], 400);
        }
        else
        {
            // \LogActivity::addToLog(Auth::user()->nama.' telah menambahkan data Kpa '.$namaKpaInput);

            $updatePpk = Ppk::find($id);
            $updatePpk->namaPpk = $request->namaPpk;
            $updatePpk->satkerId = $request->satkerId;
            $updatePpk->save();

            $satker = DB::table('tbl_satker')
                ->where('id', $request->satkerId)
                ->first();

            $result = [
                'id' => $updatePpk->id,
                'namaPpk' => $request->namaPpk,
                'namaSatker' => $satker->namaSatker
            ];

            return response()->json([
                'message' => 'Berhasil mengubah data',
                'result' => $result
            ], 200);
        }

        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah mengubah data PPK '. $request->namaPpk);
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
        $result = Ppk::find($id);
        $result->isDeleted = 1;
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah menghapus data PPK '. $result->namaPpk);
        return response()->json(compact('result'), 200);
    }
}
