<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Queries\GetNotificationsQuery;
use App\Bus\Contracts\QueryBusContract;
use App\Bus\Contracts\CommandBusContract;
use App\Commands\Notification\CreateNotificationCommand;
use App\Http\Resources\Api\V1\NotificationIndexResource;
use App\Http\Requests\Api\V1\Notification\CreateNotificationRequest;

class NotificationController extends Controller
{
    public function __construct(
        protected CommandBusContract $commandBus,
        protected QueryBusContract $queryBus,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(
                data: NotificationIndexResource::collection($this->queryBus->ask(new GetNotificationsQuery($request)))
            );
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNotificationRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $this->commandBus->dispatch(new CreateNotificationCommand($request->toDto()));
            DB::commit();
            return $this->successResponse(
                message: 'Notification created successfully',
                data: $data
            );
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->handleException($ex);
        }
    }
}
