<?php

namespace Raim\FluxNotify\Http\Controllers;

use Raim\FluxNotify\Helpers\NotificationHelper;
use Raim\FluxNotify\Helpers\SendNotificationHelper;
use Raim\FluxNotify\Http\Resources\NotificationResource;
use Raim\FluxNotify\Models\SentPushNotification;
use Raim\FluxNotify\Models\User;

use Illuminate\Http\Request;

class NotificationController
{
    /**
     * @OA\Get(
     *    path="/v2/notifications/unread",
     *    operationId="getUnreadNotificationNotifications",
     *    security={{"sanctum":{}}},
     *    tags={"Notifications"},
     *    summary="Get count of unread notification messages",
     *    description="Get count of unread notification messages",
     *    @OA\Parameter(name="type", in="query", description="client, store, news ", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    public function getUnread(Request $request)
    {
        // dd('sAsa');
        $user = $request->user();

        $unreadNotifications = SentPushNotification::query()
            ->where('status', NotificationHelper::STATUS_UNREAD)
            ->where('user_id', $user->id)
            ->count();

        return response()->json([
            'count' => $unreadNotifications
        ]);
    }

    /**
     * @OA\Get(
     *    path="/v2/notifications/{slug}",
     *    operationId="getUserNotifications",
     *    security={{"sanctum":{}}},
     *    tags={"Notifications"},
     *    summary="Get notifications  by slug",
     *    description="Get notifications  by slug",
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"slug"},
     *            @OA\Property(property="slug", type="string", format="string", example="news,store,client"),
     *         ),
     *      ),
     *    @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    public function getUserNotifications($slug)
    {
        $user = auth('sanctum')->user();

        $notifications = User::findOrFail($user->id)->sentPushNotifications()
            ->whereHas('notificationType', fn($query) => $query->where('slug', $slug))
            ->isNotOld()
            ->when($slug != SendNotificationHelper::NOTIFICATION_TYPE_NEWS, fn($query) => $query->has('order')->with('order'))
            ->orderByRaw("CASE WHEN sent_push_notifications.status!='" . NotificationHelper::STATUS_READ . "'THEN 1 ELSE 2 END ASC")
            ->orderBy("created_at", "desc")
            ->get();

        return NotificationResource::collection($notifications)->additional(['success' => true]);
    }
    public function getNotifications()
    {
        $user = auth('sanctum')->user();

        $notifications = User::findOrFail($user->id)->sentPushNotifications()
            // ->whereHas('notificationType', fn($query) => $query->where('slug', $slug))
            ->isNotOld()
            // ->when($slug != SendNotificationHelper::NOTIFICATION_TYPE_NEWS, fn($query) => $query->has('order')->with('order'))
            ->orderByRaw("CASE WHEN sent_push_notifications.status!='" . NotificationHelper::STATUS_READ . "'THEN 1 ELSE 2 END ASC")
            ->orderBy("created_at", "desc")
            ->get();

        return NotificationResource::collection($notifications)->additional(['success' => true]);
    }

    /**
     * @OA\Post(
     *      path="/v2/notifications/updateUnread",
     *      operationId="updateUnreadNotification",
     *      security={{"sanctum":{}}},
     *      tags={"Notifications"},
     *      summary="update notification message",
     *      description="update notification message",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"notifacation_id"},
     *            @OA\Property(property="notifacation_id", type="integer", format="integer", example="1"),
     *            @OA\Property(property="type", type="string", format="string", example="client"),
     *            @OA\Property(property="all", type="boolean", format="boolean", example="1"),
     *         ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="success"),
     *             @OA\Property(property="data",type="object", example="token")
     *          )
     *       )
     *  )
     */
    public function updateUnread(Request $request)
    {
        $user = $request->user();
        $type = $request->input('type');
        $unreadNotifications = SentPushNotification::query()
            ->where('status', NotificationHelper::STATUS_UNREAD)
            ->where('user_id', $user->id)
            ->when($type && !empty($type), fn($query) => $query->whereHas('notificationType' , fn($query) => $query->where('slug', $type)))
            ->get();


        if (isset($request->notifacation_ids) && is_array($request->notifacation_ids)) {
            SentPushNotification::whereIn('id', $request->notifacation_ids)->update(['status' => NotificationHelper::STATUS_READ]);
        } else {
            foreach ($unreadNotifications as $notification) {
                $notification->status = NotificationHelper::STATUS_READ;
                $notification->save();
            }
        }

        return response()->noContent();
    }
}
