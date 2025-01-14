<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $prodi = Prodi::orderBy('nama_prodi')->get();
        $mhs = Mahasiswa::all();

        // return $mhs;
        for ($i=0; $i < count($mhs); $i++) { 
            $mhs[$i]->prodi=Prodi::find($mhs[$i]->prodi_id);
        }
        // return $mhs;
        return view('admin.mahasiswa.index', compact('jurusan','prodi','mhs'));
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
        $this->validate($request, [
            'nama_mahasiswa' => 'required',
            'nim_mahasiswa' => 'required',
            'jk' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'prodi_id' => 'required',
        ]);

        $prodi = Mahasiswa::create([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'nim_mahasiswa' => $request->nim_mahasiswa,
            'jk' => $request->jk,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'prodi_id' => $request->prodi_id,
        ]);

        //buat role user
        User::create([
            'name' => $request->nama_mahasiswa,
            'email' => $request->nim_mahasiswa,
            'password' =>  Hash::make('12345'),
            'level' => '2'
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data prodi baru!');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Mahasiswa::find($id);
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $prodi = Prodi::orderBy('nama_prodi')->get();
        $send=[
            "data"=>$data,
            "jurusan"=>$jurusan,
            "prodi"=>$prodi
        ];
        return view('admin.mahasiswa.edit',$send);
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
   
    }
    public function updateV2(Request $request, $id)
    {
        $data=Mahasiswa::find($id);
        $data->nama_mahasiswa=$request->nama_mahasiswa;
        $data->nim_mahasiswa=$request->nim_mahasiswa;
        $data->tmp_lahir=$request->tmp_lahir;
        $data->tgl_lahir=$request->tgl_lahir;
        $data->prodi_id=$request->prodi_id;
        $data->jk=$request->jk;
        $data->save();

        //update user disini

        return redirect('/mahasiswa')->with('success', 'Berhasil mengubah data');
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
    public function destroyV2($id)
    {
        $data=Mahasiswa::find($id);
        $data->delete();

        //delete user disini

        return redirect('/mahasiswa')->with('success', 'Berhasil Menghapus data');
    }
    //
}
