<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        $kategoriItems = KategoriItem::all();

        return view('kategori_items.index.index', compact('kategoriItems'));
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $kategoriItem = [];
        } else {
            $kategoriItem = KategoriItem::find($id);
        }
        $data['kategoriItem'] = $kategoriItem;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = KategoriItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('kode', 'nama')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function singleView($kode)
    {
        $data['data'] = KategoriItem::where('kode', $kode)->first();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $kategoriItem = new KategoriItem;
            $kode = KategoriItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 3, '0', STR_PAD_LEFT);
        } else {
            $kategoriItem = KategoriItem::find($id);
            $kode = $kategoriItem->kode;
        }

        $kategoriItem->kode = $kode;
        $kategoriItem->nama = $request->nama;
        $kategoriItem->save();

        return redirect('kategori-items')->with('success', 'Kategori item saved successfully.');
    }

    public function delete($id)
    {
        $kategoriItem = KategoriItem::findOrFail($id);
        $kategoriItem->delete();

        return redirect('kategori-items')->with('success', 'Kategori item deleted successfully.');
    }
}
