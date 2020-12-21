<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\IpMessaging\V2\Service\User;

use Twilio\Options;
use Twilio\Values;

abstract class UserChannelOptions {
    /**
     * @param string $notificationLevel The push notification level to assign to
     *                                  the User Channel
     * @param int $lastConsumedMessageIndex The index of the last Message that the
     *                                      Member has read within the Channel
     * @param \DateTime $lastConsumptionTimestamp The ISO 8601 based timestamp
     *                                            string that represents the
     *                                            datetime of the last Message read
     *                                            event for the Member within the
     *                                            Channel
     * @return UpdateUserChannelOptions Options builder
     */
    public static function update($notificationLevel = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE) {
        return new UpdateUserChannelOptions($notificationLevel, $lastConsumedMessageIndex, $lastConsumptionTimestamp);
    }
}

class UpdateUserChannelOptions extends Options {
    /**
     * @param string $notificationLevel The push notification level to assign to
     *                                  the User Channel
     * @param int $lastConsumedMessageIndex The index of the last Message that the
     *                                      Member has read within the Channel
     * @param \DateTime $lastConsumptionTimestamp The ISO 8601 based timestamp
     *                                            string that represents the
     *                                            datetime of the last Message read
     *                                            event for the Member within the
     *                                            Channel
     */
    public function __construct($notificationLevel = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE) {
        $this->options['notificationLevel'] = $notificationLevel;
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
    }

    /**
     * The push notification level to assign to the User Channel. Can be: `default` or `muted`.
     *
     * @param string $notificationLevel The push notification level to assign to
     *                                  the User Channel
     * @return $this Fluent Builder
     */
    public function setNotificationLevel($notificationLevel) {
        $this->options['notificationLevel'] = $notificationLevel;
        return $this;
    }

    /**
     * The index of the last [Message](https://www.twilio.com/docs/chat/rest/message-resource) in the [Channel](https://www.twilio.com/docs/chat/channels) that the Member has read.
     *
     * @param int $lastConsumedMessageIndex The index of the last Message that the
     *                                      Member has read within the Channel
     * @return $this Fluent Builder
     */
    public function setLastConsumedMessageIndex($lastConsumedMessageIndex) {
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        return $this;
    }

    /**
     * The [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) timestamp of the last [Message](https://www.twilio.com/docs/chat/rest/message-resource) read event for the Member within the [Channel](https://www.twilio.com/docs/chat/channels).
     *
     * @param \DateTime $lastConsumptionTimestamp The ISO 8601 based timestamp
     *                                            string that represents the
     *                                            datetime of the last Message read
     *                                            event for the Member within the
     *                                            Channel
     * @return $this Fluent Builder
     */
    public function setLastConsumptionTimestamp($lastConsumptionTimestamp) {
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.IpMessaging.V2.UpdateUserChannelOptions ' . \implode(' ', $options) . ']';
    }
}