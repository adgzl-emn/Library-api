<?php

namespace App\Http\Controllers;

use App\Models\Visitors;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class VisitoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getVisitory($id)
    {
        try {
            $visitory = Visitors::findOrFail($id);

            $response = [
                'Ziyaretci Adi Soyadi' => $visitory->name . ' '. $visitory->last_name,
                'Ziyaretci email' => $visitory->email,
                'Ziyaretciyi Olusturan' => $visitory->createdBy->name
            ];

            if ($visitory->updatedBy) {
                $response['Ziyaretciyi Guncelleyen'] = $visitory->updatedBy->name;
            } else {
                $response['Ziyaretciyi Guncelleyen'] = 'Güncelleme yapılmadı';
            }

            return response()->json($response, 200);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'], 500);
        }
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email',
        ]);

        try {

            $visitory = Visitors::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'created_user' => $this->user->id
            ]);

            return response()->json([
                'Ziyaretci Olusturuldu' => $visitory,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.' . $e->getMessage()], 500);
        }

    }


    public function update(Request $request , $id)
    {

        try {

            $visitory = Visitors::findOrFail($id);
            $visitory->name = $request->name;
            $visitory->last_name = $request->last_name;
            $visitory->email = $request->email;
            $visitory->updated_user = $this->user->id;
            $visitory->save();


            return response()->json([
                'Ziyateci Guncellendi' => $visitory,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.' . $e->getMessage()], 500);
        }

    }

    public function delete($id)
    {
        try {
            $visitory = Visitors::findOrFail($id);
            $visitory->delete();
            return response()->json(['Uye Silindi'], 200);

        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'  . $e->getMessage()], 500);
        }
    }


}
