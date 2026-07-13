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

        /**
         * Apply rate limiting to the 'store', 'update', and 'destroy' methods of the controller.
         * The 'throttle:60,1' middleware limits the number of requests to 60 requests per minute for these methods, helping to prevent abuse and ensure fair usage of the API.
         */
        $this->middleware('throttle:api')->only(['store', 'update', 'destroy']);
        //$this->middleware('throttle:60,1')->only(['store', 'update', 'destroy']);

        /**
         * Guard against unauthorized access to the controller's methods.
         * The 'auth:sanctum' middleware ensures that the user is authenticated using Laravel Sanctum.
         * The 'except' method allows unauthenticated users to access the 'index' and 'show' methods, which are typically used for listing and viewing events.
         */
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        /**
         * The 'authorizeResource' method is used to automatically apply authorization checks for the specified resource (in this case, the Event model) to the controller's methods.
         * It maps the controller's methods to the corresponding policy methods defined in the EventPolicy class.
         * For example, the 'index' method will check the 'viewAny' policy method, the 'show' method will check the 'view' policy method,
         * the 'store' method will check the 'create' policy method, the 'update' method will check the 'update' policy method, and
         * the 'destroy' method will check the 'delete' policy method.
         */
        $this->authorizeResource(Event::class, 'event');
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

            /**
             * Use policies manually to check if the user has permission to view events:
             *
             * if ($request->user()->cannot('viewAny', Event::class)) {
             *      Log::error("You do not have permission to view events.");
             *      abort(403);
             * }
             */

            $query = Event::query();

            $size = $request->input('size', 10);

            if ($request->has('include')) {
                $query = $this->loadRelationships($query);
            }

            $events = $query->latest()->paginate($size);

            return EventResource::collection($events);

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching events.'], 500);

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
            return response()->json(['message' => 'An error occurred while creating the event.'], 500);

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
            return response()->json(['message' => 'An error occurred while fetching the event'], 500);

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

            /**
             * Check if the user has permission to update the event with gate
             * We do not require to use Gate directly
             *
             * if (Gate::denies('update-event', $event)) {
             *     abort(403, 'You do not have permission to update this event.');
             * }
             *
             * $this->authorize('update-event', $event);
             */

            /**
             * Use policies manually to check if the user has permission to update the event with policy:
             *
             * if ($request->user()->cannot('update', $event)) {
             *      Log::error("You cannot update the event with id: " . $event->id);
             *      abort(403);
             * }
             */

            $event->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time')
            ]);

            return new EventResource($this->loadRelationships($event));

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while updating the event'], 500);

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
            return response()->json(['message' => 'An error occurred while deleting the event'], 500);

        }
    }
}
