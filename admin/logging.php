<?php
// Include config file
require_once "../config.php";

function logUserAction($actionType, $userId, $userName, $eventId = null, $eventName = null) {
    global $conn;
    $sql = "INSERT INTO admin_logs (action_type, user_id, user_name, event_id, event_name) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisis", $actionType, $userId, $userName, $eventId, $eventName);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
