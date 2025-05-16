<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations;

    public function __construct()
    {
        $this->relations = ['user', 'attendees', 'attendees.user'];
    }

    /**
     * Display a listing of events.
     * @param Request $request
     * @return AnonymousResourceCollection | JsonResponse
     * @throws Exception
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $query = Event::query();

            $size = $request->input('size', 10);

            // Load relationships if specified
            if ($request->has('include')) {
                $query = $this->loadRelationships($query);
            }

            $events = $query->latest()->paginate($size);

            return EventResource::collection($events);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while fetching events.',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Store a newly created event in storage.
     * @param StoreEventRequest $request
     * @return JsonResponse | EventResource
     * @throws Exception
     */
    public function store(StoreEventRequest $request): JsonResponse|EventResource
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

            return new EventResource($this->loadRelationships($event));

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while creating the event.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Display the specified event.
     * @param Event $event
     * @return JsonResponse | EventResource
     * @throws Exception
     */
    public function show(Event $event): JsonResponse|EventResource
    {
        try {

            return new EventResource($this->loadRelationships($event));

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while fetching the event with id: ' . $event->id,
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Update the specified event in storage.
     * @param UpdateEventRequest $request
     * @param Event $event
     * @return JsonResponse | EventResource
     * @throws Exception
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse|EventResource
    {
        try {

            $event->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time')
            ]);

            return new EventResource($this->loadRelationships($event));

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the event with id: ' . $event->id,
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Remove the specified event from storage.
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Event $event): JsonResponse
    {
        try {

            $event->delete();

            return response()->json(['message' => 'Event deleted successfully with id: ' . $event->id]);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the event with id: ' . $event->id,
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
