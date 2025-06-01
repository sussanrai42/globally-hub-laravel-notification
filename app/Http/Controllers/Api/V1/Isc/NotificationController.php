<?php

namespace App\Http\Controllers\Api\V1\Isc;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Enums\NotificationStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Bus\Contracts\CommandBusContract;
use App\Commands\Notification\UpdateNotificationStatusCommand;
use App\Http\Requests\Api\V1\Isc\UpdateNotificationStatusRequest;

class NotificationController extends Controller
{
    public function __construct(
        protected CommandBusContract $commandBus
    ) {}
    public function updateStatus(UpdateNotificationStatusRequest $request, string $notificationId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->commandBus->dispatch(new UpdateNotificationStatusCommand($notificationId, NotificationStatus::from($request->status)));
            DB::commit();
            return $this->successResponse(
                message: 'Notification status updated successfully'
            );
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->handleException($ex);
        }
    }
}
