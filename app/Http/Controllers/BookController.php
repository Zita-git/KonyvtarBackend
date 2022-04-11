<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        return response($books);
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
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new StoreBookRequest())->rules());
        if ($validator->fails()) {
            $errormsg = "";
            foreach ($validator->errors()->all() as $error) {
                $errormsg .= $error . "";
            }
            $errormsg = trim($errormsg);
            return response()->json($errormsg, 400);
        }
        $book = new Book();
        $book->fill($request->all());
        $book->save();
        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "Amegadott azonosítóval nem található könyv."], 404);
        }
        return response()->json($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if ($request->isMethod('PUT')) {

            $validator = Validator::make($request->all(), (new StoreBookRequest())->rules());
            if ($validator->fails()) {
                $errormsg = "";
                foreach ($validator->errors()->all() as $error) {
                    $errormsg .= $error . "";
                }
                $errormsg = trim($errormsg);
                return response()->json($errormsg, 400);
            }
        }
        $book=Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "Amegadott azonosítóval nem található könyv."], 404);
        }
        $book->fill($request->all());
        $book->save;
        return response()->json($book, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $book=Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "Amegadott azonosítóval nem található könyv."], 404);
        }
        Book::destroy($id);
        return response()->noContent();
        
    }
    public function rentForAWeek(Request $request, int $id)
    {
        //
        
    }
}
