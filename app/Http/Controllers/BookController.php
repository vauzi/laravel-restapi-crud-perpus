<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
        // request file
        $image = $request->file('image')->store('image/book', 'public');
        try {
            // create new book
            $book = Book::query()->create([
                'name'          => $request->input('name'),
                'image'         => 'storage/' . $image,
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
