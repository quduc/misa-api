<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Car\CarRequest;
use App\Services\Api\NotificationService;
use App\Services\Api\UserNotificationService;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService     $notificationService,
        private UserNotificationService $userNotificationService
    )
    {
    }

    public function index(CarRequest $request)
    {
        $params   = [
            'status'  => ACTIVE,
            'user_id' => auth('user')->id(),
        ];
        $metaSeo  = [
            'meta_title' => 'お知らせ一覧'
        ];
        $viewData = [
            'meta_seo'      => MetaSeoRender::setMetaSeo($metaSeo),
            'notifications' => $this->notificationService->get($params)
        ];
        return view('pages.notification.index')->with($viewData);
    }

    public function show($id)
    {
        $userId       = auth()->id();
        $notification = $this->notificationService->show($id, $userId);
        if (!$notification || ($notification->user_id && $notification->user_id != $userId) || $notification->status != ACTIVE) {
            abort(404);
        }

        // update read at
        $this->userNotificationService->store(['notification_id' => $notification->id, 'user_id' => $userId]);

        $metaSeo = [
            'meta_title' => $notification->title
        ];

        $viewData = [
            'meta_seo'     => MetaSeoRender::setMetaSeo($metaSeo),
            'notification' => $notification,
        ];

        return view('pages.notification.show')->with($viewData);
    }
}
