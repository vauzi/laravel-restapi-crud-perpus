<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        try {
            $book = Book::query()->get();
            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'get all books successfully',
                'data' => $book
            ]);
        } catch (QueryException $e) {
            // response failed
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        //validation request body
        $validation = Validator::make($request->all(), [
            'name'          => ['required', 'min:3'],
            'image'         => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'kategori'      => ['required'],
            'author'        => ['required'],
            'penerbit'      => ['required', 'min:3'],
            'tahun_terbit'  => ['required', 'numeric', 'min:4'],
            'deskripsi'     => ['required'],
        ], [
            'required'  => ':attribute harus di isi',
            'image'     => ':attribute harus berupa foto',
            'numeric'   => ':attribute harus berupa angka',
            'mimes'     => 'format :attribute harus bertipe: :values',
            'max'       => ':attribute maksimal 2 mb'
        ]);
        if ($validation->fails()) { // check validation error jika error status code 400
            return response()->json($validation->errors(), 400);
        }

        // request file
        $image = $request->file('image')->store('image/book', 'public'); // upload file to public/image/book/filename.image
        try {
            // create new book
            $book = Book::query()->create([
                'name'          => $request->input('name'),
                'image'         => 'storage/' . $image, // menambahkan path image folder storage
                'kategori_id'   => $request->input('kategori'),
                'author_id'     => $request->input('author'),
                'penerbit'      => $request->input('penerbit'),
                'tahun_terbit'  => $request->input('tahun_terbit'),
                'deskripsi'     => $request->input('deskripsi'),
            ]);

            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'Created successfully',
                'data' => $book
            ]);
        } catch (QueryException $e) {
            // response failed
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        //try catch hendle errors
        try {
            // get book by id
            $book = Book::query()->find($id);
            // repsonse successful
            return response()->json([
                'status'    => 'success',
                'message'   => 'Get book by id successfully',
                'data'      => $book
            ], 201);
        } catch (QueryException $e) {
            // response failed
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        //validation request body
        $validation = Validator::make($request->all(), [
            'name'          => ['required', 'min:3'],
            'image'         => ['image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'kategori'      => ['required'],
            'author'        => ['required'],
            'penerbit'      => ['required', 'min:3'],
            'tahun_terbit'  => ['required', 'numeric', 'min:4'],
            'deskripsi'     => ['required'],
        ], [
            'required'  => ':attribute harus di isi',
            'image'     => ':attribute harus berupa foto',
            'min'       => ':attribute minimal :values',
            'numeric'   => ':attribute harus berupa angka',
            'mimes'     => 'format :attribute harus bertipe: :values',
        ]);
        if ($validation->fails()) { // check validation error jika error status code 400
            return response()->json($validation->errors(), 400);
        }

        try {
            $book = Book::query()->find($id); // query getBook by id

            // check if the book not null
            if ($request->file('image') !== null) {
                $image = 'strage/' . $request->file('image')->store('image/book', 'public');
            } else {
                $image = $book->image;
            }
            $book->update([
                'name'          => $request->input('name'),
                'image'         => $image,
                'kategori_id'   => $request->input('kategori'),
                'author_id'     => $request->input('author'),
                'penerbit'      => $request->input('penerbit'),
                'tahun_terbit'  => $request->input('tahun_terbit'),
                'deskripsi'     => $request->input('deskripsi')
            ]);

            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'Updated book successfully'
            ]);
        } catch (QueryException $e) {
            // response failed
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::query()->find($id);
            unlink(public_path($book->image)); // response successfully
            $book->delete(); // delete book in database
            return response()->json([
                'status' => 'success',
                'message' => 'Book deleted successfully',
                'data' => $book
            ]);
        } catch (QueryException $e) {
            // response failed
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
