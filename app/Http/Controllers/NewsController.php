<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\News;
use File;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware', ['except' => ['index', 'show', 'getActive', 'isActive']]);
    }

    public function index()
    {
        // $result = [];
        
        $result = DB::table('tbl_news')
            ->join('tbl_user', 'tbl_news.userId', '=', 'tbl_user.id')
            ->select('tbl_news.*', 'tbl_user.nama')
            ->where('tbl_news.isDeleted', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        // \LogActivity::addToLog(Auth::user()->roleName.' telah menambahkan data temuan baru ');

        return response()->json(compact('result'), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'bgImage' => 'required|mimes:jpeg,jpg,png,bmp,tiff,gif|nullable|max:10000'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('bgImage'))
        {
            $fileNameWithExt = $request->file('bgImage')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('bgImage')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName).'_'.time().'.'.$extension;
            $path = $request->file('bgImage')->storeAs('public/images', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = 'noimage.png';
        }

        if (Auth::user()->roleId > 30)
        {
            $satkerId = null;
        }
        else
        {
            $satkerId = Auth::user()->satkerId;
        }

        $namaUser = DB::table('tbl_user')
            ->where('id', Auth::user()->id)
            ->first();

        $insert = News::create([
            'title' => $request->title,
            'body' => $request->body,
            'bgImage' => $fileNameToStore,
            'userId' => Auth::user()->id,
            'satkerId' => $satkerId,
            'isActive' => 1
        ]);

        $result = [
            'title' => $request->title,
            'body' => $request->body,
            'bgImage' => $fileNameToStore,
            'userId' => Auth::user()->id,
            'nama' => $namaUser->nama,
            'satkerId' => $satkerId,
            'isActive' => 1
        ];

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menambah Berita baru berjudul '.$title);
        
        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'result' => $result
        ], 200);
    }

    function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'body' => 'required',
        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors(), 400);
        // }

        $news = News::find($id);

        if ($request->hasFile('bgImage'))
        {
            $oldFile = public_path("public/images/{$news->bgImage}");
            if (File::exists($oldFile)) { // unlink or remove previous image from folder
                unlink($oldFile);
            }

            $fileNameWithExt = $request->file('bgImage')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('bgImage')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('bgImage')->storeAs('public/images/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = $news->bgImage;
        }

        if (Auth::user()->roleId > 30)
        {
            $satkerId = null;
        }
        else
        {
            $satkerId = Auth::user()->satkerId;
        }

        $update = News::find($id);
        $update->title = $request->title;
        $update->body = $request->body;
        $update->bgImage = $fileNameToStore;
        $update->userId = Auth::user()->id;
        $update->satkerId = $satkerId;
        $update->save();

        $namaUser = DB::table('tbl_user')
            ->where('id', Auth::user()->id)
            ->first();

        $result = [
            'id' => $id,
            'title' => $request->title,
            'body' => $request->body,
            'bgImage' => $fileNameToStore,
            'userId' => Auth::user()->id,
            'nama' => $namaUser->nama,
            'satkerId' => $satkerId,
            'isActive' => $update->isActive
        ];
            
        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah mengubah berita berjudul '.$title);

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
    public function show($id)
    {
        $result = News::find($id)->first();

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menghapus berita berujudl '.$title);

        return response()->json([
            'message' => 'Berhasil merequest data',
            'result' => $result
        ], 200);
    }

    public function getActive()
    {
        $result = DB::table('tbl_news')
            ->join('tbl_user', 'tbl_news.userId', '=', 'tbl_user.id')
            ->select('tbl_news.*', 'tbl_user.nama')
            ->where('tbl_news.isActive', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menghapus berita berujudl '.$title);

        return response()->json([
            'message' => 'Berhasil merequest data',
            'result' => $result
        ], 200);
    }

    public function isActive(Request $request, $id)
    {
        $news = News::find($id);
        $news->isActive = $request->isActive;
        $news->save();

        $result = News::find($id)->first();

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menghapus berita berujudl '.$title);

        if ($request->isActive == 0)
        {
            $message = 'Berhasil me-non-aktifkan berita';
        }
        else
        {
            $message = 'Berhasil mengaktifkan berita';
        }

        return response()->json([
            'message' => $message,
            'result' => $result
        ], 200);
    }

    public function destroy($id)
    {
        $result = News::find($id);
        $result->isDeleted = '1';
        $result->save();

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menghapus berita berujudl '.$title);

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);
    }
}
