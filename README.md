# ServerSideEventTrackerPHP

A simple server side event tracker for Google Analytics written in PHP and intended for RESTful APIs


# Sample usage

<?php
function sendComplexEventAnalysisNew($cmd, $userId){
    $newTracker = new GoogleAnalysisTracker('UA-XXXXXXXX-X', 'example.com', $userId);

    $newTracker->trackEvent($_SERVER, $category, $actionName);
}
?>
