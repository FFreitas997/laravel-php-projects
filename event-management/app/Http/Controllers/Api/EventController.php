<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $size = $request->input('size', 10);

            $query = Event::query();

            $events = $query
                ->with(['user', 'attendees'])
                ->orderBy('created_at', 'desc')
                ->paginate($size);

            return response()->json([$events]);

        }catch (Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching events.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created event in storage.
     * @param StoreEventRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        try {
            // TODO get the authenticated userID
            $event = Event::query()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'user_id' => 663
            ]);

            return response()->json([$event], 201);

        }catch (Exception $e){
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while creating the event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified event.
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(string $id): JsonResponse
    {
        try {
            $event = Event::with(['user'])->findOrFail($id);

            return response()->json([$event]);

        }catch (Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching the event with id: ' . $id,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified event in storage.
     * @param UpdateEventRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateEventRequest $request, string $id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);

            $event->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time')
            ]);

            return response()->json([$event]);

        }catch (Exception $e){
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the event with id: ' . $id,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified event from storage.
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);

            $event->delete();

            return response()->json(['message' => 'Event deleted successfully.']);

        }catch (Exception $e){
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the event with id: ' . $id,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
