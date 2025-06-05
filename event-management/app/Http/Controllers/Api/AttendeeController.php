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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations;

    public function __construct()
    {
        $this->relations = ['user'];
    }

    /**
     * Display a listing of attendees.
     *
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

            return response()->json([
                'message' => 'An error occurred while fetching attendees.',
                'error' => $e->getMessage()
            ], 500);

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

            return response()->json([
                'message' => 'An error occurred while creating the attendee.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Display the specified attendee.
     * @param Event $event
     * @param Attendee $attendee
     * @return AttendeeResource | JsonResponse
     * @throws Exception
     */
    public function show(Event $event, Attendee $attendee): AttendeeResource|JsonResponse
    {
        try {

            return new AttendeeResource($this->loadRelationships($attendee));

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while fetching the attendee with id: ' . $attendee->id,
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Remove the specified attendee from storage.
     * @param Event $event
     * @param Attendee $attendee
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request, Event $event, Attendee $attendee): JsonResponse
    {
        try {

/*            // Check if the user has permission to delete the attendee
            if (Gate::denies('delete-attendee', [$event, $attendee])) {
                Log::error("You cannot delete the attendee with id: " . $attendee->id);
                return response()->json(['message' => 'You do not have permission to delete this attendee.'], 403);
            }*/

            // Check if the user has permission to delete the attendee with policy
            if ($request->user()->cannot('delete', [$event, $attendee])) {
                Log::error("You cannot delete the attendee with id: " . $attendee->id);
                return response()->json(['message' => 'You do not have permission to delete this attendee.'], 403);
            }

            $attendee->delete();

            return response()->json(['message' => 'Attendee deleted successfully']);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the attendee with id: ' . $attendee->id,
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
