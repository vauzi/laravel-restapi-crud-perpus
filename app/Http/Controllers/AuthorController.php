<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index()
    {
        // try catch: heanding errors
        try {
            $author = Author::query()->get(); //get all authors. mengambil semua data dari database tabel authors

            // response success. akan di tampilkan saat proses berhasil/success
            return response()->json([
                'status' => 'success',
                'message' => 'get all author information successfully',
                'data' => $author
            ]);
        } catch (QueryException $e) {

            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        // validation request body. required(harus di isi)
        $validation = Validator::make($request->all(), [
            'name'          => ['required', 'min:3'],
            'image'         => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'tgl_lahir'     => ['required'],
            'gender'        => ['required'],
            'alamat'        => ['required'],
        ], [ //response validation error message
            'required'      => ':attribute harus di isi',
            'image'         => ':attribute harus berupa foto',
            'mimes'         => ':attribute, format file harus :values',
            'max'           => ':attribute maksimal 2Mb',
            'min'           => ':attribute minimal 3 karakter',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }
        $image = $request->file('image')->store('image/author', 'public'); // upload image file and path image = public/image/author/nameFile dan 
        try {
            // insert data into authors table
            $author = Author::query()->create([
                'name'      => $request->input('name'),
                'image'     => 'storage/' . $image,
                'tgl_lahir' => $request->input('tgl_lahir'),
                'gender'    => $request->input('gender'),
                'alamat'    => $request->input('alamat'),
            ]);

            // response successfully
            return response()->json([
                'status'    => 'success',
                'message'   => 'Created successfully',
                'data'      => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function show($id)
    {
        try {
            $author = Author::query()->find($id); //get author by id
            // response success
            return response()->json([
                'status'    => 'success',
                'message'   => 'show author by id successfully',
                'data'      => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        //validation request body
        $validation = Validator::make($request->all(), [
            'name'          => ['required', 'min:3'],
            'image'         => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'tgl_lahir'     => ['required'],
            'gender'        => ['required'],
            'alamat'        => ['required'],
        ], [
            //response validation error messages
            'required'      => ':attribute harus di isi',
            'image'         => ':attribute harus berupa foto',
            'mimes'         => ':attribute, format file harus type: :values',
            'max'           => ':attribute maksimal 2Mb',
            'min'           => ':attribute minimal 3 karakter'
        ]);
        if ($validation->fails()) { //cechk validation request body
            return response()->json($validation->errors(), 400);
        }
        // try catch: hendling errors
        try {
            $author = Author::query()->find($id); // get athor by id
            // check image file not null
            if ($request->file('image') !== null) {
                unlink(public_path($author->image)); //delete image file
                $image = 'storage/' . $request->file('image')->store('image/author', 'public'); // upluad image file
            } else {
                $image = $author->image;
            }
            // upadte author
            $author->update([
                'name'      => $request->input('name'),
                'image'     => $image,
                'tgl_lahir' => $request->input('tgl_lahir'),
                'gender'    => $request->input('gender'),
                'alamat'    => $request->input('alamat'),
            ]);
            // response successful
            return response()->json([
                'status' => 'success',
                'message' => 'Author successfully updated',
                'data' => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $author = Author::query()->find($id);
            unlink(public_path($author->image)); // delete image in storage folder
            $author->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'delete author successfully',
                'data' => $author,
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
}
