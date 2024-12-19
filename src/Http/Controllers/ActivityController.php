<?php

namespace AbdulQuadri\ActivityLogger\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AbdulQuadri\ActivityLogger\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('user')
            ->latest();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->paginate(15);

        return view('activity-logger::activities.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        return view('activity-logger::activities.show', compact('activity'));
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('activity-logger.index')
            ->with('success', __('activity-logger::messages.deleted_successfully'));
    }
}