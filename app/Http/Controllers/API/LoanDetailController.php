<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LoanDetail;
use Illuminate\Http\Request;

class LoanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = LoanDetail::all()->toArray();
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

        LoanDetail::create($input);

        return response()->json(['success' => 'Loan created successfully']);

    }

    public function edit($id)
    {
        $loans = LoanDetail::find($id);
        return response()->json($loans);
    }


    public function update($id, Request $request)
    {
        $loans = LoanDetail::find($id);
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

        return response()->json(['success' => 'Loan update successfully']);
    }

    public function delete($id)
    {
        $loans = LoanDetail::find($id);
        $loans->delete();
        return response()->json(['success' => 'Loan deleted successfully']);

    }
}
