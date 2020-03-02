<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\SupplierSimpanRequest;
use App\Http\Requests\SupplierUpdateRequest;

//model
use App\Supplier;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.supplier.index');
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
    public function store(SupplierSimpanRequest $request)
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
                        'data' => ['Gagal menyimpan data Supplier! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        
                DB::beginTransaction();
        
                try {
        
                    $supplier = new Supplier();
                    $supplier->nama_supplier = $input['nama'];
                    $supplier->email = $input['email'];
                    $supplier->alamat = $input['alamat'];
                    $supplier->nomor_hp = $input['nomor'];
                    $supplier->user_id = 1;
                    $supplier->save();

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
        $supplier = Supplier::find($id);

        return response()->json([
            'nama_supplier' => $supplier->nama_supplier,
            'nomor_hp' => $supplier->nomor_hp,
            'email' => $supplier->email,
            'alamat' => $supplier->alamat,
            'user_id' => $supplier->user->name,
            'tgl_input' => $supplier->created_at->format('d M Y - H:i:s')

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);

        return response()->json([
            'id' => $supplier->id,
            'nama_supplier' => $supplier->nama_supplier,
            'nomor_hp' => $supplier->nomor_hp,
            'email' => $supplier->email,
            'alamat' => $supplier->alamat
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierUpdateRequest $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $supplier = Supplier::find($id);
                $userCari = Supplier::where('email', $input['email'])->first();
                if ($userCari != null) {
                    if ($supplier->id != $userCari->id) {
                        return response()->json([
                            'data' => ['Email ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($supplier != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $supplier);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data supplier! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data supplier! Supplier Aplikasi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $supplier) {
        DB::beginTransaction();
        try {
            $dataubah = [
                'nama_supplier' => $input['name'],
                'email' => $input['email'],
                'alamat' => $input['alamat'],
                'nomor_hp' => $input['nomorhp'],
                'updated_at' => date('Y/m/d H:i:s')
            ];
            DB::table('supplier')
                ->where('id', $supplier->id)
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
        $supplier = Supplier::find($id);

        $hasil = $this->simpanTransaksiDelete($supplier);

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

    protected function simpanTransaksiDelete($supplier)
    {
        DB::beginTransaction();

        try {
            $supplier->delete();
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

    public function apiSupplier()
    {
        $supplier = Supplier::all();
        $cacah = 0;
        $data = [];
        foreach ($supplier as $i => $d) {
        	$data[$cacah] = [ 
                $d->id,
                $d->nama_supplier,
                $d->nomor_hp,
                $d->email,
                $d->user->name,
                $d->id
        	];

        	$cacah++;    
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function carisupplier()
    {
        $supplier = Supplier::all();
        $cacah = 0;
        $data = [];
        foreach ($supplier as $i => $d) {
        	$data[$cacah] = [ 
                $d->nama_supplier,
                $d->email,
                $d->nomor_hp,
                $d->alamat,
                $d->id
        	];

        	$cacah++;    
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
