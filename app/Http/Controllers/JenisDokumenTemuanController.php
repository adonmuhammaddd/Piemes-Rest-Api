<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JenisDokumenTemuan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JenisDokumenTemuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('tbl_jenis_dokumen_temuan')
            ->where('isDeleted', 0)
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
            'jenisDokumenTemuan' => 'required|string|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $jenisDokumenTemuan = $request->get('jenisDokumenTemuan');
        $jenisDokumenTemuanDb = JenisDokumenTemuan::query()
            ->where('jenisDokumenTemuan', $jenisDokumenTemuan)
            ->count();

        if ($jenisDokumenTemuanDb > 0)
        {
            return response()->json([
                'message' => 'Jenis Dokumen Temuan Sudah Ada',
                'result' => $jenisDokumenTemuan
            ], 400);
        }
        else
        {
            $result = JenisDokumenTemuan::create([
                'jenisDokumenTemuan' => $jenisDokumenTemuan
            ]);

            // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menambahkan data Jenis Dokumen Temuan '. $jenisDokumenTemuan);

            return response()->json(compact('result'), 200);
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
        $jenisDokumenTemuanDb = JenisDokumenTemuan::query()
            ->where('jenisDokumenTemuan', $request->jenisDokumenTemuan)
            ->count();

        if ($jenisDokumenTemuanDb > 0)
        {
            return response()->json([
                'message' => 'Jenis Dokumen Temuan Sudah Ada',
                'result' => $jenisDokumenTemuan
            ], 400);
        }
        else
        {
            $result = JenisDokumenTemuan::find($id);
            $result->jenisDokumenTemuan = $request->jenisDokumenTemuan;
            $result->save();
    
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data Jenis Dokumen Temuan '. $request->jenisDokumenTemuan);
            return response()->json(compact('result'), 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = JenisDokumenTemuan::find($id);
        $result->isDeleted = 1;
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah menghapus data Jenis Dokumen Temuan '. $result->jenisDokumenTemuan);
        return response()->json(compact('result'), 200);
    }
}
