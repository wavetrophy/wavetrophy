<?php

namespace App\Service\Firebase;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Moment\Moment;

/**
 * Class NotificationService
 */
class NotificationService extends FirebaseService
{
    /**
     * Send a message to a device
     *
     * @param string $token The device token of the user
     * @param string $message The message that is in the body of the notification
     * @param array $data The data of the notification
     */
    public function toDevice(string $token, string $message, array $data = [])
    {
        $notifcationData = array_replace_recursive($this->getNotificationDefaultData(), [
            'message' => $message,
        ], $data);
        $this->sendTo('token', $token, $message, $notifcationData);
    }

    /**
     * Send a message to a topic
     *
     * @param string $topic The topic where the users are subscribed to
     * @param string $message The message that is in the body of the notification
     * @param array $data
     */
    public function toTopic(string $topic, string $message, array $data = [])
    {
        $notifcationData = array_replace_recursive($this->getNotificationDefaultData(), $data, [
            'message' => $message,
        ]);
        $this->sendTo('topic', $topic, $message, $notifcationData);
    }

    /**
     * Send a message.
     *
     * @param string $type
     * @param string $value
     * @param string $message
     * @param $data
     */
    private function sendTo(string $type, string $value, string $message, $data)
    {
        $notification = Notification::create('WAVETROPHY', $message);
        $cloudMessage = CloudMessage::withTarget($type, $value)
            ->withNotification($notification)
            ->withData(['json' => json_encode($data)]);

        $this->getMessaging()->send($cloudMessage);
    }

    /**
     * Get default notification data
     *
     * @return array
     */
    private function getNotificationDefaultData()
    {
        $date = new Moment();
        $data = [
            'open' => '/wave/stream',
            'creator' => $this->getCurrentUserData(),
            'issued_at' => $date->format(),
        ];

        return $data;
    }
}
