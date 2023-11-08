<?php

namespace Raim\FluxNotify\Http\Controllers;

use Raim\FluxNotify\Helpers\NotificationHelper;
use Raim\FluxNotify\Http\Resources\NotificationTypeResource;
use Raim\FluxNotify\Models\NotificationType;

class NotificationTypeController
{
    /**
     * @OA\Get(
     *    path="/v2/notification-types",
     *    operationId="notificationType",
     *    tags={"Notification types"},
     *    summary="Get list of notification types",
     *    description="Get list of  notification types",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    public function index()
    {
        $user = auth('sanctum')->user();
        $query = NotificationType::query();
        if ($user) {
            $query->withCount([
                'sentPushNotifications' => fn($query) => $query
                    ->isNotOld()
                    ->where('status', NotificationHelper::STATUS_UNREAD)
                    ->where('user_id', $user->id)
            ])->with([
                'sentPushNotifications' => fn($query) => $query->isNotOld()
                    ->where('user_id', $user->id)
                    ->latest()
                    ->limit(1)
            ]);
        }

        return NotificationTypeResource::collection($query->get());
    }
}
