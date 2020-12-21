<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use App\User;
use File;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('jwtmiddleware', ['except' => 'login']);
    // }

    public function login(Request $request)
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials))
        {
            return response()->json(['error' => 'Unauthorized'], 401); 
        }
        $user = DB::table('tbl_user')->where('username', $request->username)->first();

        $result = DB::table('tbl_user')
                ->select('tbl_user.id', 'tbl_user.nama', 'tbl_user.email', 'tbl_user.username', 'tbl_user.foto', 'tbl_user.roleId')
                ->where('username', $request->username)
                ->first();

        return $this->respondWithToken($token, $result);
    }

    public function sendMail($id)
    {
        $user = User::findOrFail($id);
        $data = array(
            'name' => $user->nama,
            'message' => 'AHSDAJKFGHASLFG'
        );

        Mail::to($user->email)->send(new SendMail($data));
        return response()->json([
            'message' => 'Success',
            'name' => $data['name'],
            'message' => $data['message'],
            'emailTo' => $user->email
        ], 200);
    }

    public function index()
    {
        $result = DB::table('view_user_records')
            ->where('isDeleted', 0)
            ->get();
        
        return response()->json(compact('result'), 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|max:255|unique:tbl_user',
            'username' => 'required|max:255|unique:tbl_user',
            'password' => 'required|string|min:6|confirmed',
            'foto' => 'mimes:jpeg,jpg,png,bmp,tiff,gif|nullable|max:10000',
            'roleId' => 'required|numeric|max:255',
            'roleName' => 'required|string|max:255'
        ]);

        $nama = $request->get('nama');
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $roleId = $request->get('roleId');
        $roleName = $request->get('roleName');

        if ($request->hasFile('foto'))
        {
            $fileNameWithExt = $request->file('foto')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName).'_'.time().'.'.$extension;
            $path = $request->file('foto')->storeAs('public/images', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = 'noimage.png';
        }

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        else
        {
            $insert = [
                'nama' => $nama,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($password),
                'roleId' => $roleId,
                'roleName' => $roleName,
                'foto' => $fileNameToStore
            ];
            // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menambahkan data User '.$nama);

            $result = [
                'nama' => $nama,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($password),
                'roleId' => $roleId,
                'roleName' => $roleName,
                'foto' => $fileNameToStore
            ];
            
            User::create($insert);
            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'result' => $result
            ], 200);
        }
    }

    public function show($id)
    {
        $result = DB::table('view_user_records')->where('id', $id)->first();

        return response()->json([
            'message' => 'Voila',
            'result' => $result
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'mimes:jpeg,jpg,png,bmp,tiff,gif|nullable|max:10000',
        ]);

        $user = User::find($id);

        if ($request->hasFile('foto'))
        {
            $oldFile = public_path("public/images/{$user->foto}");
            if (File::exists($oldFile)) { // unlink or remove previous image from folder
                unlink($oldFile);
            }

            $fileNameWithExt = $request->file('foto')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName).'_'.time().'.'.$extension;
            $path = $request->file('foto')->storeAs('public/images', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = $user->foto;
        }
        
        $nama = $request->get('nama');
        $NIP = $request->get('NIP');
        $satkerId = $request->get('satkerId');
        $email = $request->get('email');
        $email2 = $request->get('email2');
        $email3 = $request->get('email3');
        $username = $request->get('username');
        $password = $request->get('password');
        $roleId = $request->get('roleId');
        $roleName = $request->get('roleName');

        $user = User::find($id);
        $user->nama = $nama;
        $user->satkerId = $satkerId;
        $user->NIP = $NIP;
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->email2 = $email2;
        $user->email3 = $email3;
        $user->roleId = $roleId;
        $user->roleName = $roleName;
        $user->foto = $fileNameToStore;
        User::find($id)->update(
            array(
                'nama' => $user->nama,
                'satkerId' => $user->satkerId,
                'NIP' => $user->NIP,
                'password' => $user->password,
                'email' => $user->email,
                'email2' => $user->email2,
                'email3' => $user->email3,
                'roleId' => $user->roleId,
                'roleName' => $user->roleName,
                'foto' => $user->foto
            )
        );

        $satker = DB::table('tbl_satker')
            ->where('id', $satkerId)
            ->first();

        $result = [
            'id' => $user->id,
            'nama' => $nama,
            'satkerId' => $satkerId,
            'namaSatker' => $satker->namaSatker,
            'NIP' => $NIP,
            'email' => $email,
            'email2' => $email2,
            'email3' => $email3,
            'username' => $username,
            'roleId' => $roleId,
            'roleName' => $roleName,
            'foto' => $fileNameToStore
        ];

        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah mengubah data User '.$nama);

        return response()->json([
            'message' => 'Berhasil mengubah data',
            'result' => $result
        ], 200);

    }

    public function getAuthenticatedUser()
    {
        return response()->json(auth()->user());
    }

    public function emailCheck(Request $request)
    {
        $emails = User::where('email', '=', $request->email)->first();
        if ($emails === null)
        {
            return response()->json(TRUE, 200);
        }
        else
        {
            return response()->json(FALSE, 200);
        }
    }

    public function usernameCheck(Request $request)
    {
        $usernames = User::where('username', '=', $request->username)->first();

        if ($usernames === null)
        {
            return response()->json(TRUE, 200);
        }
        else
        {
            return response()->json(FALSE, 200);
        }
    }

    public function passwordCheck(Request $request, $id)
    {
        $user = User::find($id);
        $passwordCheck = Hash::check($request->get('password'), $user->password);
        if ($passwordCheck)
        {
            return response()->json(TRUE, 200);
        }
        else
        {
            return response()->json(FALSE, 200);
        }
    }

    public function passwordChange(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:6|confirmed'
        ]);

        $user = User::find($id);
        $user->password = Hash::make($request->newPassword);
        User::find($id)->update(array('password' => $user->password));

        return response()->json('Password berhasil diganti', 200);
    }

    public function refresh() 
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token, $result)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 260,
            'user' => $result
        ], 200);
    }
}