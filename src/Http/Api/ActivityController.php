<?php

namespace AbdulQuadri\ActivityLogger\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AbdulQuadri\ActivityLogger\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('user')
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json([
            'data' => $query->paginate($request->per_page ?? 15)
        ]);
    }

    public function show(Activity $activity)
    {
        return response()->json([
            'data' => $activity->load('user')
        ]);
    }

    public function userActivities(Request $request, $userId)
    {
        $activities = Activity::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => $activities
        ]);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return response()->json([
            'message' => __('activity-logger::messages.deleted_successfully')
        ]);
    }
}