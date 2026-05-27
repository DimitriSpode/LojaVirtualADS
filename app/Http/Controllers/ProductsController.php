<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function create()
    {
        return view('products.create', [
            'types' => Type::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request) // Poderia se chamar salvar, save, poderia ser function vaiza

    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $data = collect($validated)->except('image')->all();
        $data['image'] = $imagePath;

        Product::create($data);

        return redirect('/products')->with('status', 'Produto cadastrado com sucesso!');
    }

    //função que irá mostrar a view de listagem
//passando como parâmetro a consulta no banco com ::all()
public function index()
{
        return view('products.index', [
            'products' => Product::with('type')->orderBy('name')->paginate(10),
        ]);
}

public function edit($id)
{
    $product = Product::findOrFail($id);

    return view('products.edit', [
        'product' => $product,
        'types' => Type::orderBy('name')->get(),
    ]);
}

public function update(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'type_id' => 'required|exists:types,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $product = Product::findOrFail($request->id);

    $imagePath = $product->image;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    $data = collect($validated)->except('image')->all();
    $data['image'] = $imagePath;

    $product->update($data);

    return redirect('/products')->with('success', 'Produto atualizado com sucesso!');
}

public function destroy($id)
{
    //select * from product where id = ?
    $product = Product::find($id);
    //deleta o produto no banco
    $product->delete();
    return redirect('/products')->with('success', 'Produto
excluído com sucesso!');
}



}
