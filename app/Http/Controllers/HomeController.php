<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $tipoAtual = $request->filled('tipo') ? (string) $request->query('tipo') : null;

        $productsQuery = Product::query()
            ->with('type')
            ->where('quantity', '>', 0)
            ->where('price', '>', 0)
            ->when(
                $request->filled('tipo'),
                fn ($q) => $q->where('type_id', $request->integer('tipo'))
            )
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->string('q')->trim();
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', '%'.$term.'%')
                        ->orWhere('description', 'like', '%'.$term.'%');
                });
            })
            ->orderBy('name');

        $products = $productsQuery->get();

        $types = Type::query()
            ->whereHas(
                'products',
                fn ($q) => $q->where('quantity', '>', 0)->where('price', '>', 0)
            )
            ->orderBy('name')
            ->get();

        return view('welcome', [
            'products' => $products,
            'types' => $types,
            'tipoAtual' => $tipoAtual,
        ]);
    }
}
