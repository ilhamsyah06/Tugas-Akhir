<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\UserSimpanRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

//model
use App\User;
use App\Absen;
use App\Gaji;
use Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.user.index');
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
    public function store(UserSimpanRequest $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $hasil = $this->simpanTransaksiCreate($input);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data user! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        
                DB::beginTransaction();
        
                try {
        
                    $user = new User();
                    $user->name = $input['nama'];
                    $user->email = $input['email'];
                    $user->password = bcrypt($input['password']);
                    $user->alamat = $input['alamat'];
                    $user->nomorhp = $input['nomor'];
                    $user->level = $input['level'];
                    $user->save();

                } catch (ValidationException $ex) {
                    DB::rollback();
                    return $ex->getMessage();;
                } catch (Exception $ex) {
                    DB::rollback();
                    return $ex->getMessage();;
                }
        
                DB::commit();
        
                return '';
            }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json([
            'nama' => $user->name,
            'email' => $user->email,
            'alamat' => $user->alamat,
            'nomor' => $user->nomorhp,
            'level' => $user->level
        ]);
    }

    public function detailuser()
    {
        $now = date('Y-m-d');
        $sekarang = Carbon::now()->addMonth(1)->format('m');        
        $id =  Auth::user()->id;
        $ambildataid = User::find($id);
        $ambildataabsen = DB::table('absen')->where('user_id', $id)->where('tgl_absen',$now)->limit(1)->get();
        $count = Absen::where('tgl_absen','=',$now)->where('user_id',$id)->count();
        $gaji = Gaji::where('user_id', $id)->where('tgl_gajian', '>=', Carbon::now()->startOfMonth())->take(1)->get();
        $countgaji = Gaji::where('user_id', $id)->where('tgl_gajian', '>=', Carbon::now()->startOfMonth())->take(1)->count();
        return view('master.user.detail_user', compact('count','ambildataid','ambildataabsen','gaji','countgaji'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'alamat' => $user->alamat,
            'nomorhp' => $user->nomorhp,
            'level' => $user->level
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $user = User::find($id);
                $userCari = User::where('email', $input['email'])->first();
                if ($userCari != null) {
                    if ($user->id != $userCari->id) {
                        return response()->json([
                            'data' => ['Email ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($user != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $user);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data user! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data user! User Aplikasi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $user) {
        DB::beginTransaction();
        try {
            $dataubah = [
                'name' => $input['name'],
                'email' => $input['email'],
                'alamat' => $input['alamat'],
                'nomorhp' => $input['nomorhp'],
                'level' => $input['level'],
                'remember_token' => str_random(60),
                'updated_at' => date('Y/m/d H:i:s')
            ];
            if ($input['ubahkatasandi'] != '0') {
                $dataubah['password'] = bcrypt($input['password']);
            }
            DB::table('users')
                ->where('id', $user->id)
                ->update($dataubah);
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }

        DB::commit();

        return '';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::find($id);

        $hasil = $this->simpanTransaksiDelete($user);

        if ($hasil === '') {
            return response()->json([
                'data' => 'Sukses Menghapus Data'
            ]);
        } else {
            return response()->json([
                'data' => ['Gagal Menghapus data! Mungkin data ini sedang digunakan oleh data di tabel lainnya!']
            ], 422);
        }
    }

    protected function simpanTransaksiDelete($user)
    {
        DB::beginTransaction();

        try {
            $user->delete();
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }

        DB::commit();

        return '';
    }

    public function apiUser()
    {
 
        $user = User::all();
        $cacah = 0;
        $data = []; //variabel untuk menyimpan data bentuk json

        foreach ($user as $i => $d) {
        	$data[$cacah] = [
                $d->id, 
                $d->name,
                $d->email,
                $d->level,//memanggil kolom dari tabel lain yg relasi
                $d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);

    }

}
