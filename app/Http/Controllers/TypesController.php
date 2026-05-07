<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class TypesController extends Controller
{
    public function index()
    {
        return view('tipos.index', [
            'types' => Type::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('tipos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:types,name',
        ]);

        Type::create($validated);

        return redirect('/types')->with('status', 'Tipo cadastrado com sucesso!');
    }

    public function edit($id)
    {
        return view('tipos.edit', [
            'type' => Type::findOrFail($id),
        ]);
    }

    public function update(Request $request)
    {
        $type = Type::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:types,name,'.$type->id,
        ]);

        $type->update($validated);

        return redirect('/types')->with('success', 'Tipo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);

        if (Product::where('type_id', $type->id)->exists()) {
            return redirect('/types')->with('error', 'Não é possível excluir: existem produtos usando este tipo.');
        }

        $type->delete();

        return redirect('/types')->with('success', 'Tipo excluído com sucesso!');
    }
}
