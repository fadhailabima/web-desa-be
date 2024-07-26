<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function createNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'titleSm' => 'required|string',
            'subtitleSm' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:51200',
            'inputDate' => 'required|date'
        ]);

        if ($validator->fails()) {
            Log::error('createIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $news = new News();
            $news->title = $request->input('title');
            $news->titleSm = $request->input('titleSm');
            $news->subtitleSm = $request->input('subtitleSm');
            $news->content = $request->input('content');
            $news->inputDate = $request->input('inputDate');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/imageNews/', $fileName);
                    $news->image = $fileName;
                } else {
                    Log::error('createIos: Invalid file upload.', ['error' => $file->getErrorMessage()]);
                    return response()->json(['message' => 'Failed to upload file', 'error' => $file->getErrorMessage()], 400);
                }
            }

            $news->save();
            Log::info('createIos: iOS created successfully.', ['data' => $news]);

            return response()->json([
                'message' => 'News created successfully',
                'data' => $news
            ]);
        } catch (\Exception $e) {
            Log::error('createIos: Error creating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateNews(Request $request, $id)
    {
        Log::info('updateIos: Request received.', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'titleSm' => 'sometimes|string',
            'subtitleSm' => 'sometimes|string',
            'content' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png|max:51200',
            'inputDate' => 'sometimes|date'
        ]);

        if ($validator->fails()) {
            Log::error('updateIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $news = News::find($id);
            if (!$news) {
                Log::error('updateIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found'], 404);
            }

            $news->title = $request->input('title', $news->title);
            $news->titleSm = $request->input('titleSm', $news->titleSm);
            $news->subtitleSm = $request->input('subtitleSm', $news->subtitleSm);
            $news->content = $request->input('content', $news->content);
            $news->inputDate = $request->input('inputDate', $news->inputDate);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/imageNews/', $fileName);
                $news->image = $fileName;
            }

            $news->save();
            Log::info('updateIos: iOS updated successfully.', ['data' => $news]);

            return response()->json([
                'message' => 'iOS updated successfully',
                'data' => $news
            ]);
        } catch (\Exception $e) {
            Log::error('updateIos: Error updating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllNews()
    {
        Log::info('getAllIos: Request received.');

        try {
            $newss = News::all()->map(function ($news) {
                $news->image_url = $news->image ? url('api/storage/app/public/imageNews/' . $news->image) : null;
                return $news;
            });
            Log::info('getAllIos: iOS records fetched successfully.', ['data' => $newss]);
            return response()->json(['data' => $newss]);
        } catch (\Exception $e) {
            Log::error('getAllIos: Error fetching iOS records.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
    public function getNewsById($id)
    {
        Log::info('getNewsById: Request received.', ['id' => $id]);

        try {
            $news = News::find($id);
            if (!$news) {
                Log::error('getNewsById: News not found.', ['id' => $id]);
                return response()->json(['message' => 'News not found. Wrong Id'], 404);
            }

            // Menambahkan image_url ke objek berita
            $news->image_url = $news->image ? url('api/storage/app/public/imageNews/' . $news->image) : null;

            Log::info('getNewsById: News record fetched successfully.', ['data' => $news]);
            return response()->json(['data' => $news]);
        } catch (\Exception $e) {
            Log::error('getNewsById: Error fetching news by ID.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteNews($id)
    {
        Log::info('deleteIos: Request received.', ['id' => $id]);

        try {
            $news = News::find($id);
            if (!$news) {
                Log::error('deleteIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found. Wrong Id'], 404);
            }

            $news->delete();
            Log::info('deleteIos: iOS deleted successfully.', ['id' => $id]);
            return response()->json(['message' => 'iOS deleted successfully']);
        } catch (\Exception $e) {
            Log::error('deleteIos: Error deleting iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
