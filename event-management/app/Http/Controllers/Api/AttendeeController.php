<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations;

    public function __construct()
    {
        $this->relations = ['user'];

        /**
         * Apply rate limiting to the 'store' and 'destroy' methods of the controller.
         * The 'throttle:60,1' middleware limits the number of requests to 60 requests per minute for these methods, helping to prevent abuse and ensure fair usage of the API.
         */
        $this->middleware('throttle:api')->only(['store', 'destroy']);
        //$this->middleware('throttle:60,1')->only(['store', 'destroy']);

        /**
         * Applying authorization checks for the Attendee resource using the 'authorizeResource' method.
         * This will automatically apply the corresponding policy methods defined in the AttendeePolicy class to the controller's methods.
         * For example, the 'index' method will check the 'viewAny' policy method, the 'show' method will check the 'view' policy method,
         * the 'store' method will check the 'create' policy method, the 'update' method will check the 'update' policy method, and
         * the 'destroy' method will check the 'delete' policy method.
         */
        $this->authorizeResource(Attendee::class, 'attendee');
    }

    /**
     * Display a listing of attendees.
     * @param Request $request
     * @param Event $event
     * @return AnonymousResourceCollection | JsonResponse
     * @throws Exception
     */
    public function index(Request $request, Event $event): AnonymousResourceCollection|JsonResponse
    {
        try {

            $size = $request->input('size', 10);

            $attendees = $event->attendees()->latest();

            if ($request->has('include')) {
                $attendees = $this->loadRelationships($attendees);
            }

            return AttendeeResource::collection($attendees->paginate($size));

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching attendees.'], 500);

        }
    }

    /**
     * Store a newly created attendee in storage.
     * @param Request $request
     * @param Event $event
     * @return JsonResponse | AttendeeResource
     * @throws Exception
     */
    public function store(Request $request, Event $event): AttendeeResource|JsonResponse
    {
        try {

            $user = $request->user();

            $attendee = $event->attendees()->create(['user_id' => $user->id]);

            return new AttendeeResource($this->loadRelationships($attendee));

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while creating the attendee.',], 500);

        }
    }

    /**
     * Display the specified attendee.
     * @param Attendee $attendee
     * @return AttendeeResource | JsonResponse
     * @throws Exception
     */
    public function show(Attendee $attendee): AttendeeResource|JsonResponse
    {
        try {

            return new AttendeeResource($this->loadRelationships($attendee));

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching the attendee with id: ' . $attendee->id], 500);

        }
    }

    /**
     * Remove the specified attendee from storage.
     * @param Request $request
     * @param Event $event
     * @param Attendee $attendee
     * @return JsonResponse
     */
    public function destroy(Request $request, Event $event, Attendee $attendee): JsonResponse
    {
        try {
            /**
             * Check if the user has permission to delete the attendee
             * We do not require to use Gate directly
             *
             * if (Gate::denies('delete-attendee', [$event, $attendee])) {
             *     abort(403, 'You do not have permission to delete this attendee.');
             * }
             *
             * $this->authorize('delete-attendee', [$request->user(), $event, $attendee]);
             */

            /**
             * Use policies manually to check if the user has permission to delete the attendee with policy:
             *
             * if ($request->user()->cannot('delete', [$event, $attendee])) {
             *      Log::error("You cannot delete the attendee with id: " . $attendee->id);
             *      abort(403);
             * }
             */

            $attendee->delete();

            return response()->json(['message' => 'Attendee deleted successfully']);

        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the attendee with id: ' . $attendee->id], 500);

        }
    }
}
