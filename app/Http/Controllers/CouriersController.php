<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCouriers;
use App\Models\Couriers;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CouriersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        //
        $data = Couriers::query()->orderBy('courier_name','asc')->paginate();
        return response()->json([
            'status' => 200,
            'courier' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validation = Validator($request->all(),[
            'nik' => 'required|unique:couriers,nik',
            'name' => 'required',
            'phone' => 'required',
            'zone' => 'required'
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors(),
                'status' => 422
            ],422);
        }
        // check apakah data tersedia di database
        $query = Couriers::query()->latest()->first();
        // buat aksi jika data tidak ada maka akan di awali oleh angka 1 dan jika ada akan di ambil data terakhir pada data courier
        if($query===null){
            $n = 1;
        }else{
            $n = $query->id +1;
        }
        $couriers = 'DTB'. str_pad($n,4,'0',STR_PAD_LEFT);
        Couriers::create([
            'courier_id' => $couriers,
            'courier_nik' => $request->nik,
            'courier_name' => $request->name,
            'courier_phone' => $request->phone,
            'courier_zone' => $request->zone,
            'user_id' => 1
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'courier berhasil di input'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $couriers)
    {
        //
        $query = Couriers::query()->where('courier_id',$couriers)->first();
        // dd($query);
        if($query===null){
            return response()->json([
                'message' => 'data tidak ketemu',
                'status' => 404
            ],404);
        }else{
            return response()->json([
                'data'=>$query,
                'status' => 200
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $couriers)
    {
        //
        $query = Couriers::query()->where('courier_id',$couriers)->first();
        if($query===null){
            return response()->json([
                'message' => 'data tidak ketemu',
                'status' => 404
            ],404);
        }else{
            $validation = Validator($request->all(),[
            
                'phone' => 'required',
                'zone' => 'required'
            ]);
            if($validation->fails()){
                return response()->json([
                    'errors' => $validation->errors(),
                    'status' => 422
                ],422);
            }
            $query->update([
                'courier_phone' =>$request->phone,
                'courier_zone' => $request->zone,
            ]);
            return response()->json([
                'status' =>200,
                'message' => 'data berhasil di update'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $couriers)
    {
        //
        $query = Couriers::query()->where('courier_id',$couriers)->first();
         if($query===null){
            return response()->json([
                'message' => 'data tidak ketemu',
                'status' => 404
            ],404);
        }else{
            $query->delete();
            return response()->json([
                'status' => 200,
                'message' => 'courier '.$couriers.' berhasil di hapus'
            ],200);
        }
    }
}
