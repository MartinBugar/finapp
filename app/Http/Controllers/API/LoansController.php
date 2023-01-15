<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loans;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loans::all()->toArray();
        return array_reverse($loans);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description',
            'value' => 'required',
            'typeID' => 'required',
            'userID',
            'date',
        ]);

        $input = $request->all();

        Loans::create($input);

        return response()->json(['success'=> 'Loan created successfully']);

    }

    public function edit($id)
    {
        $loans = Loans::find($id);
        return response()->json($loans);
    }


    public function update($id, Request $request)
    {
        $loans = Loans::find($id);
        $request->validate([
            'name' => 'required',
            'description',
            'value' => 'required',
            'typeID' => 'required',
            'userID',
            'date',
        ]);

        $input = $request->all();
        $loans->update($input);

        return response()->json(['success'=> 'Loan update successfully']);
    }

    public function delete($id)
    {
        $loans = Loans::find($id);
        $loans->delete();
        return response()->json(['success'=> 'Loan deleted successfully']);

    }
}
