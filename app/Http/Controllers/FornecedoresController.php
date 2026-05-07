<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class FornecedoresController extends Controller
{
    public function index()
    {
        return view('fornecedores.index', [
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:32',
        ]);

        Supplier::create($validated);

        return redirect('/fornecedores')->with('status', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        return view('fornecedores.edit', [
            'supplier' => Supplier::findOrFail($id),
        ]);
    }

    public function update(Request $request)
    {
        $supplier = Supplier::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:32',
        ]);

        $supplier->update($validated);

        return redirect('/fornecedores')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return redirect('/fornecedores')->with('success', 'Fornecedor excluído com sucesso!');
    }
}
