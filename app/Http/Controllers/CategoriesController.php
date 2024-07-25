<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Catlocs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function tambahKategoriFasilitas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $categories = new Categories();
        $categories->kategori = $request->input('kategori');
        $categories->save();

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $categories
        ]);
    }

    public function getKategoriFasilitas()
    {
        $kategori = Categories::all();

        return response()->json([
            'message' => 'Berhasil mendapatkan semua kategori',
            'data' => $kategori
        ]);
    }

    public function hapusKategoriFasilitas($id)
    {
        $kategori = Categories::find($id);

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
        ]);
    }

    public function tambahKategoriLokasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_lokasi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $catlocs = new Catlocs();
        $catlocs->kategori_lokasi = $request->input('kategori_lokasi');
        $catlocs->save();

        return response()->json([
            'message' => 'Kategori lokasi berhasil ditambahkan',
            'data' => $catlocs
        ]);
    }

    public function hapusKategoriLokasi($id)
    {
        $kategori_lokasi = Catlocs::find($id);

        if (!$kategori_lokasi) {
            return response()->json([
                'message' => 'Kategori lokasi tidak ditemukan',
            ], 404);
        }

        $kategori_lokasi->delete();

        return response()->json([
            'message' => 'Kategori lokasi berhasil dihapus',
        ]);
    }
    public function getKategoriLokasi()
    {
        $kategoriLokasi = Categories::all();

        return response()->json([
            'message' => 'Berhasil mendapatkan semua kategori lokasi',
            'data' => $kategoriLokasi
        ]);
    }
}
