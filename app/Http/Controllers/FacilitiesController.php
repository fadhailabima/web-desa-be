<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Facilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FacilitiesController extends Controller
{
    public function createFacilities(Request $request, $category_id)
    {

        $category = Categories::find($category_id);
        if (!$category) {
            return response()->json(['message' => 'Divisi tidak ditemukan'], 404);
        }
        Log::info('createIos: Request received.', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'titleSm' => 'required|string',
            'subtitleSm' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:51200',
        ]);

        if ($validator->fails()) {
            Log::error('createIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $facilities = new Facilities();
            $facilities->title = $request->input('title');
            $facilities->titleSm = $request->input('titleSm');
            $facilities->subtitleSm = $request->input('subtitleSm');
            $facilities->content = $request->input('content');
            $facilities->category_id = $category_id;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/imageFacilities/', $fileName);
                    $facilities->image = $fileName;
                } else {
                    Log::error('createIos: Invalid file upload.', ['error' => $file->getErrorMessage()]);
                    return response()->json(['message' => 'Failed to upload file', 'error' => $file->getErrorMessage()], 400);
                }
            }

            $facilities->save();
            Log::info('createIos: iOS created successfully.', ['data' => $facilities]);

            return response()->json([
                'message' => 'Facilities created successfully',
                'data' => $facilities
            ]);
        } catch (\Exception $e) {
            Log::error('createIos: Error creating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateFacilities(Request $request, $id)
    {
        Log::info('updateIos: Request received.', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'titleSm' => 'required|string',
            'subtitleSm' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:51200',
        ]);

        if ($validator->fails()) {
            Log::error('updateIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $facilities = Facilities::find($id);
            if (!$facilities) {
                Log::error('updateIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found'], 404);
            }

            $facilities->title = $request->input('title', $facilities->title);
            $facilities->titleSm = $request->input('titleSm', $facilities->titleSm);
            $facilities->subtitleSm = $request->input('subtitleSm', $facilities->subtitleSm);
            $facilities->content = $request->input('content', $facilities->content);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/imageFacilities/', $fileName);
                $facilities->cover = $fileName;
            }

            $facilities->save();
            Log::info('updateIos: iOS updated successfully.', ['data' => $facilities]);

            return response()->json([
                'message' => 'Facilities updated successfully',
                'data' => $facilities
            ]);
        } catch (\Exception $e) {
            Log::error('updateIos: Error updating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteFacilities($id)
    {
        Log::info('deleteIos: Request received.', ['id' => $id]);

        try {
            $facilities = Facilities::find($id);
            if (!$facilities) {
                Log::error('deleteIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found. Wrong Id'], 404);
            }

            $facilities->delete();
            Log::info('deleteIos: iOS deleted successfully.', ['id' => $id]);
            return response()->json(['message' => 'iOS deleted successfully']);
        } catch (\Exception $e) {
            Log::error('deleteIos: Error deleting iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllFacilities($category_id)
    {
        Log::info('getAllFacilities: Request received.');

        try {
            $facilities = Facilities::where('category_id', $category_id)->get()->map(function ($facility) {
                $facility->image_url = $facility->image ? url('api/storage/app/public/imageFacilities/' . $facility->image) : null;
                return $facility;
            });
            Log::info('getAllFacilities: Facility records fetched successfully.', ['data' => $facilities]);
            return response()->json(['data' => $facilities]);
        } catch (\Exception $e) {
            Log::error('getAllFacilities: Error fetching facility records.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getFacById($id)
    {
        Log::info('getIosById: Request received.', ['id' => $id]);

        try {
            $facilities = Facilities::find($id);
            if (!$facilities) {
                Log::error('getIosById: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found. Wrong Id'], 404);
            }
            Log::info('getIosById: iOS record fetched successfully.', ['data' => $facilities]);
            return response()->json(['data' => $facilities]);
        } catch (\Exception $e) {
            Log::error('getIosById: Error fetching iOS by ID.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
