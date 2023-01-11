<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Posts::all()->toArray();
        return array_reverse($posts);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'value' => 'required',
            'type' => 'required',
            'typeID' => 'required',
            'userID',
            'date',
            'file',
            'pdfName',
        ]);

        $input = $request->all();

        $pdfName = NULL;
        if ($pdf = $request->file('file')) {
            $destinationPath = 'pdf/';
            $pdfName = date('YmdHis') . "." . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfName);
            $input['pdf'] = $pdfName;
        }

        Posts::create($input);

        return response()->json(['success'=> 'Post created successfully']);

    }

    public function edit($id)
    {
        $post = Posts::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = Posts::find($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'value' => 'required',
            'type' => 'required',
            'typeID' => 'required',
            'date',
            'userID',
            'pdf',
            'pdfName',
        ]);

        $input = $request->all();

        $pdfNameOfTheSource = NULL;
        if ($pdf = $request->file('pdf')) {
            $destinationPath = 'pdf/';
            $pdfNameOfTheSource = date('YmdHis') . "." . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfNameOfTheSource);
            $input['pdf'] = $pdfNameOfTheSource;
            if ($post->pdf) {
                unlink('pdf/' . $post->pdf);
            }
        }

        $post->update($input);

        return response()->json(['success'=> 'Post update successfully']);
    }

    public function delete($id)
    {
        $post = Posts::find($id);
        if ($post->pdf) {
            unlink('pdf/' . $post->pdf);
        }
        $post->delete();
        return response()->json(['success'=> 'Post deleted successfully']);

    }
}
