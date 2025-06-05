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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations;

    public function __construct()
    {
        $this->relations = ['user', 'attendees', 'attendees.user'];

        // guard against unauthenticated access at the controller level
        //$this->middleware('auth:sanctum')->except(['index', 'show']);
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
            if ($request->user()->cannot('viewAny', Event::class)) {
                Log::error("You do not have permission to view events.");
                return response()->json(['message' => 'You do not have permission to view events.'], 403);
            }

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
            $user = $request->user();

            $event = Event::query()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'user_id' => $user->id
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
                'message' => 'An error occurred while fetching the event',
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

/*            if (Gate::denies('update-event', $event)) {
                Log::error("You cannot update the event");
                return response()->json(['message' => 'You do not have permission to update this event.'], 403);
            }*/

            // Check if the user has permission to update the event with policy
            if ($request->user()->cannot('update', $event)) {
                Log::error("You cannot update the event with id: " . $event->id);
                return response()->json(['message' => 'You do not have permission to update this event.'], 403);
            }

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
                'message' => 'An error occurred while updating the event',
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

            return response()->json(['message' => 'Event deleted successfully']);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the event',
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
