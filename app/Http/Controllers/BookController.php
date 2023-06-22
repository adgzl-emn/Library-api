<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookController extends Controller
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

    public function getBook($id)
    {
        try {
            $book = Book::findOrFail($id);

            $response = [
                'Kitap Adi' => $book->book_name,
                'Kategori Adi' => $book->category->name,
                'Kategoriyi Olusturan' => $book->createdBy->name
            ];

            if ($book->updatedBy) {
                $response['Kategoriyi Guncelleyen'] = $book->updatedBy->name;
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
            'book_name' => 'required|string',
            'category_id' => 'required',
        ]);

        try {
            //$user = JWTAuth::parseToken()->authenticate();

            $book = Book::create([
                'book_name' => $request->input('book_name'),
                'category_id' => $request->input('category_id'),
                'slug' => Str::slug($request->input('book_name')),
                'created_user' => $this->user->id
            ]);
            $categoryName = $book->category->name;

            return response()->json([
                'Kitap Oluşturuldu' => $book,
                'Kategori' => $categoryName,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.' . $e->getMessage()], 500);
        }

    }


    public function update(Request $request , $id)
    {

        try {

            $book = Book::findOrFail($id);
            $book->book_name = $request->input('book_name');
            $book->category_id = $request->input('category_id');
            $book->updated_user = $this->user->id;
            $book->save();

            $categoryName = $book->category->name;

            return response()->json([
                'Kitap Guncellendi' => $book,
                'Kategori' => $categoryName
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.' . $e->getMessage()], 500);
        }

    }

    public function delete($id)
    {
        try {
            $book = Book::findOrFail($id);

            $book->delete();
            return response()->json(['Kitap Silindi'], 200);

        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Hata.'  . $e->getMessage()], 500);
        }
    }

}
