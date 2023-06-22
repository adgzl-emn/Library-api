<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getCategory($id)
    {

        try {
            $category = Category::findOrFail($id);

            $response = [
                'Kategori Adi' => $category->name,
                'Kategoriyi Olusturan' => $category->createdBy->name
            ];

            if ($category->updatedBy) {
                $response['Kategoriyi Guncelleyen'] = $category->updatedBy->name;
            } else {
                $response['Kategoriyi Guncelleyen'] = 'Güncelleme yapılmadı';
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
            'name' => 'required|string'
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $category = Category::create([
                'name' => $request->input('name'),
                'created_user' => $user->id
            ]);

            return response()->json(['Kategori Olusturuldu ' => $category], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'], 500);
        }

    }

    public function update(Request $request , $id)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $category = Category::findOrFail($id);

            $category->name = $request->input('name');
            $category->updated_user = $user->id;
            $category->save();

            return response()->json(['Kategori Guncellendi ' => $category], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $category = Category::findOrFail($id);

            $category->delete();
            return response()->json(['Kategori Silindi'], 200);

        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'], 500);
        }
    }

}
