<?php

namespace App\QueryHandlers;

use App\Queries\GetNotificationsQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class GetNotificationsQueryHandler
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function handle(GetNotificationsQuery $getNotificationsQuery): LengthAwarePaginator
    {
        $filterable = $getNotificationsQuery->request->except('filters');
        $filterable['filters'][] = [
            'field' => 'user_id',
            'value' => auth()->user()->id
        ];
        return $this->notificationRepository->paginate($filterable);
    }
}
