<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Expenses;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expenses::all()->toArray();
        return array_reverse($expenses);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'value' => 'required',
            'typeID' => 'required',
            'userID',
            'date',
        ]);

        $input = $request->all();

        Expenses::create($input);

        return response()->json(['success'=> 'Expenses created successfully']);

    }

    public function edit($id)
    {
        $expens = Expenses::find($id);
        return response()->json($expens);
    }


    public function update($id, Request $request)
    {
        $expens = Expenses::find($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'value' => 'required',
            'typeID' => 'required',
            'userID',
            'date',
        ]);

        $input = $request->all();
        $expens->update($input);

        return response()->json(['success'=> 'Expenses update successfully']);
    }

    public function delete($id)
    {
        $expens = Expenses::find($id);
        $expens->delete();
        return response()->json(['success'=> 'Expenses deleted successfully']);

    }
}
