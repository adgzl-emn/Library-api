<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function checkBook(Request $request) //kullaniciya direkt kiralamadan kontrol etmesi icin
    {
        $book = Delivery::where('book_id', $request->book_id)->first();
        $visit = Delivery::where('visitory_id', $request->visitory_id)->first();

        if ($book)
        {
            return response()->json(['message' => 'Bu kitap Suan Baska kullanicida.'], 200);
        }
        elseif($visit)
        {
            return response()->json(['message' => 'Elinizdeki Kitabi Iade etmeniz gerekmelidir.'], 200);
        }
        return response()->json(['message' => 'Bu kitabi Kiralayabilirsiniz.'], 200);
    }

    public function rentBook(Request $request)
    {
        $bookId = $request->input('book_id');
        $visitorId = $request->input('visitory_id');

        try {

            if (!$this->checkBookAvailability($bookId)) {
                return response()->json(['message' => 'Bu kitap Mevcut Degil.'], 200);
            }

            //kitap başka bir kullanıcıda mı
            $bookActivity = Delivery::where('book_id', $bookId)
                ->whereNull('returned_at')
                ->first();

            if ($bookActivity) {
                return response()->json(['message' => 'Bu kitap şu anda başka bir kullanıcıda.'], 200);
            }

            //ziyaretçide başka bir kitap var mi
            $visitorActivity = Delivery::where('visitory_id', $visitorId)
                ->whereNull('returned_at')
                ->first();

            if ($visitorActivity) {
                return response()->json(['message' => 'Elinizdeki kitabı iade etmeniz gerekmektedir.'], 200);
            }

            // Kitabı kiralama işlemi
            $bookActivity = Delivery::create([
                'book_id' => $bookId,
                'visitory_id' => $visitorId,
                'returned_at' => $request->input('returned_at')
            ]);


            return response()->json([
                'message' => 'Kitap başarıyla kiralandı.',
                'Kiralanan Kitap' => $bookActivity->getBook->book_name,
                'Kiralanan Ziyaretci' => $bookActivity->getVisitory->name . ' ' . $bookActivity->getVisitory->last_name
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }

    public function checkBookAvailability($bookId)
    {
        $book = Book::find($bookId);
        if (!$book) {
            return false; // Kitap bulunamadı
        }
        return true; // Kitap mevcut

    }

    public function returnBook($bookId)
    {
        $book = Delivery::where('book_id', $bookId)->first();


        if (!$book) {
            return response()->json(['message' => 'Bu kitap kiralanmamış veya zaten iade edilmiş.'], 404);
        }


        $book->returned_at = Carbon::now();
        $book->save();

        return response()->json(['message' => 'Kitap başarıyla iade edildi.'], 200);
    }












}
