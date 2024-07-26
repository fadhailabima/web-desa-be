<?php

namespace App\Http\Controllers;

use App\Models\Catlocs;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationsController extends Controller
{
    public function tambahLokasi(Request $request, $catlocs_id)
    {
        $category = Catlocs::find($catlocs_id);
        if (!$category) {
            return response()->json(['message' => 'Divisi tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $lokasi = new Locations();
        $lokasi->catlocs_id = $catlocs_id;
        $lokasi->nama_lokasi = $request->nama_lokasi;
        $lokasi->latitude = $request->latitude;
        $lokasi->longitude = $request->longitude;
        $lokasi->save();

        return response()->json([
            'message' => 'Lokasi berhasil ditambahkan',
            'data' => $lokasi
        ]);
    }

    public function hapusLokasi($id)
    {
        $lokasi = Locations::find($id);

        if (!$lokasi) {
            return response()->json([
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        }

        $lokasi->delete();

        return response()->json([
            'message' => 'Lokasi berhasil dihapus'
        ]);
    }

    public function tampilkanSemuaLokasi()
    {
        $lokasi = Locations::all();

        return response()->json([
            'message' => 'Berhasil mendapatkan semua lokasi',
            'data' => $lokasi
        ]);
    }

    public function tampilkanLokasiBerdasarkanCatlocsId($catlocs_id)
    {
        $lokasi = Locations::where('catlocs_id', $catlocs_id)->get();

        if ($lokasi->isEmpty()) {
            return response()->json([
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan lokasi',
            'data' => $lokasi
        ]);
    }
}
