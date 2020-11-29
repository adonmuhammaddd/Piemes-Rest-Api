<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Satker;

class SatkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }

    public function index()
    {
        $result = DB::table('tbl_satker')->where('isDeleted', 0)->get();

        return response()->json([
            'message' => 'Success',
            'result' => $result
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaSatker' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        $satkerInput = $request->get('namaSatker');
        $alamat = $request->get('alamat');

        $satker = Satker::query()
            ->where('namaSatker', $satkerInput)
            ->count();

        if ($satker > 0)
        {
            return response()->json([
                'message' => 'Data sudah ada',
                'result' => $satkerInput
            ], 200);
        }
        else
        {
            // $log = \LogActivity::addToLog(Auth::user()->nama.' telah menambahkan data Satker '. $satkerInput);

            $insert = [
                'namaSatker' => $satkerInput,
                'alamat' => $alamat
            ];
            Satker::create($insert);
            $satkerId = DB::table('tbl_satker')
                ->latest('created_at')
                ->first();
            $result = [
                'id' => $satkerId->id,
                'namaSatker' => $satkerInput,
                'alamat' => $alamat
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
        $satkerInput = $request->get('namaSatker');
        $alamat = $request->get('alamat');

        $satker = Satker::query()
            ->where('namaSatker', $satkerInput)
            ->count();

        // $log = \LogActivity::addToLog(Auth::user()->nama.' telah menambahkan data Satker '. $satkerInput);

        $updateSatker = Satker::find($id);
        $updateSatker->namaSatker = $satkerInput;
        $updateSatker->alamat = $alamat;
        $updateSatker->save();

        $result = [
            'id' => $updateSatker->id,
            'namaSatker' => $request->satkerInput,
            'alamat' => $alamat
        ];

        return response()->json([
            'message' => 'Berhasil menambahkan data',
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
        $result = Satker::find($id);
        $result->isDeleted = '1';
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menghapus data Satker ');
        
        return response()->json(compact('result'), 200);
    }
}
