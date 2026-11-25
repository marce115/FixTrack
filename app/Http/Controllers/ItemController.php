<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::with('category')->withCount('reports');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        $items = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        $latest = Item::latest('id')->first();
        $nextNum = $latest ? $latest->id + 1 : 1;
        $suggestedCode = 'BRG-'.str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);

        return view('items.create', compact('categories', 'suggestedCode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50', 'unique:items,item_code'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'item_code.unique' => 'Kode barang sudah terdaftar.',
            'item_code.required' => 'Kode barang wajib diisi.',
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'location.required' => 'Lokasi penempatan barang wajib diisi.',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Data barang '.$validated['name'].' berhasil ditambahkan.');
    }

    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50', 'unique:items,item_code,'.$item->id],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Data barang '.$item->name.' berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        if ($item->reports()->exists()) {
            return back()->with('error', 'Barang ini tidak dapat dihapus karena memiliki riwayat laporan kerusakan.');
        }

        $itemName = $item->name;
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang '.$itemName.' berhasil dihapus.');
    }
}
