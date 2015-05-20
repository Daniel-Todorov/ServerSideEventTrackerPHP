<?php

class GAEventTracker
{
    private $googleAnalyticsEndpoint = 'http://www.google-analytics.com/collect';
    private $trackingId;
    private $domainName;
    private $userId;

    function __construct($trackingId, $domainName, $userId)
    {
        $this->setTrackingId($trackingId);
        $this->setDomainName($domainName);
        $this->setUserId($userId);
    }

    /**
     * @param $trackingId string
     * @throws InvalidArgumentException
     */
    private function setTrackingId($trackingId)
    {
        if (!$trackingId) {
            throw new InvalidArgumentException('Tracking ID is required.');
        }

        $this->trackingId = $trackingId;
    }

    /**
     * @param $domainName string
     * @throws InvalidArgumentException
     */
    private function setDomainName($domainName)
    {
        if (!$domainName) {
            throw new InvalidArgumentException('Domain name is required.');
        }

        $this->domainName = $domainName;
    }

    /**
     * @param mixed $userId
     * @throws InvalidArgumentException
     */
    private function setUserId($userId)
    {
        if (!$userId) {
            throw new InvalidArgumentException('User id is required.');
        } else if (!is_numeric($userId)) {
            throw new InvalidArgumentException('User id must be a numebr of numeric string');
        }

        $this->userId = $userId;
    }

    /**
     * @param $serverData array
     * @param null $category
     * @param null $action
     * @param null $label
     */
    public function trackEvent($serverData, $category = null, $action = null, $label = null)
    {
        $data = array(
            'v' => 1,
            'tid' => $this->trackingId,
            'ds' => 'mobileAPI',
            'cid' => $this->generateUUID(),
            'uid' => $this->userId,
            'uip' => $serverData['REMOTE_ADDR'],
            'ua' => $serverData['HTTP_USER_AGENT'],
            't' => 'event',
            'ec' => $category,
            'ea' => $action,
            'el' => $label
        );

        $this->sendData($data);
    }

    /**
     * Generates the 'cid' based on the userId
     * @return string
     */
    private function generateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            $this->userId, $this->userId,
            $this->userId,
            $this->userId,
            $this->userId,
            $this->userId, $this->userId, $this->userId
        );
    }

    /**
     * @param $data array
     */
    private function sendData($data)
    {
        $content = http_build_query($data);
        $content = utf8_encode($content);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->googleAnalyticsEndpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

}
