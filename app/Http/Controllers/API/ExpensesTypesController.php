<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExpensesTypes;
use Illuminate\Http\Request;

class ExpensesTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expensesTypes = ExpensesTypes::all()->toArray();
        return array_reverse($expensesTypes);
    }

    public function add(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'userID',
        ]);

        $input = $request->all();
        ExpensesTypes::create($input);

        return response()->json(['success'=> 'Expenses created successfully']);

    }

    public function edit($id)
    {
        $expensType = ExpensesTypes::find($id);
        return response()->json($expensType);
    }

    public function update($id, Request $request)
    {
        $expensType = ExpensesTypes::find($id);
        $request->validate([
            'type' => 'required',
            'userID',
        ]);

        $input = $request->all();
        $expensType->update($input);

        return response()->json(['success'=> 'Expenses update successfully']);
    }

    public function delete($id)
    {
        $expensType = ExpensesTypes::find($id);
        $expensType->delete();
        return response()->json(['success'=> 'Expenses deleted successfully']);

    }

}
