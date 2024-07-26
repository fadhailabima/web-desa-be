<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class VideosController extends Controller
{
    public function createVideos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'titleSm' => 'required|string',
            'subtitleSm' => 'required|string',
            'content' => 'required|string',
            'video' => 'required|string',
            'inputDate' => 'required|date'
        ]);

        if ($validator->fails()) {
            Log::error('createIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $videos = new Videos();
            $videos->title = $request->input('title');
            $videos->titleSm = $request->input('titleSm');
            $videos->subtitleSm = $request->input('subtitleSm');
            $videos->content = $request->input('content');
            $videos->inputDate = $request->input('inputDate');

            $videos->save();
            Log::info('createIos: iOS created successfully.', ['data' => $videos]);

            return response()->json([
                'message' => 'News created successfully',
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            Log::error('createIos: Error creating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateVideos(Request $request, $id)
    {
        Log::info('updateIos: Request received.', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'titleSm' => 'sometimes|string',
            'subtitleSm' => 'sometimes|string',
            'content' => 'sometimes|string',
            'video' => 'sometimes|string',
            'inputDate' => 'sometimes|date'
        ]);

        if ($validator->fails()) {
            Log::error('updateIos: Validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $videos = Videos::find($id);
            if (!$videos) {
                Log::error('updateIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found'], 404);
            }

            $videos->title = $request->input('title', $videos->title);
            $videos->titleSm = $request->input('titleSm', $videos->titleSm);
            $videos->subtitleSm = $request->input('subtitleSm', $videos->subtitleSm);
            $videos->content = $request->input('content', $videos->content);
            $videos->video = $request->input('video', $videos->video);
            $videos->inputDate = $request->input('inputDate', $videos->inputDate);

            $videos->save();
            Log::info('updateIos: iOS updated successfully.', ['data' => $videos]);

            return response()->json([
                'message' => 'iOS updated successfully',
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            Log::error('updateIos: Error updating iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getVideo($id)
    {
        $video = Videos::find($id);

        if (!$video) {
            Log::error('tampilkanVideo: Video not found.', ['id' => $id]);
            return response()->json([
                'message' => 'Video not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Video found successfully',
            'data' => $video
        ]);
    }

    public function getAllVideos()
    {
        $videos = Videos::all();

        return response()->json([
            'message' => 'Berhasil mendapatkan semua video',
            'data' => $videos
        ]);
    }

    public function deleteVideos($id)
    {
        Log::info('deleteIos: Request received.', ['id' => $id]);

        try {
            $videos = Videos::find($id);
            if (!$videos) {
                Log::error('deleteIos: iOS not found.', ['id' => $id]);
                return response()->json(['message' => 'iOS not found. Wrong Id'], 404);
            }

            $videos->delete();
            Log::info('deleteIos: iOS deleted successfully.', ['id' => $id]);
            return response()->json(['message' => 'iOS deleted successfully']);
        } catch (\Exception $e) {
            Log::error('deleteIos: Error deleting iOS.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
