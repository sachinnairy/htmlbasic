<?php

/*!
 * ifsoft.co.uk engine v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $gcm_regId = isset($_POST['gcm_regId']) ? $_POST['gcm_regId'] : '';
    $ios_fcm_regId = isset($_POST['ios_fcm_regId']) ? $_POST['ios_fcm_regId'] : '';

    $android_msg_fcm_regid = isset($_POST['android_msg_fcm_regid']) ? $_POST['android_msg_fcm_regid'] : '';
    $ios_msg_fcm_regid = isset($_POST['ios_msg_fcm_regid']) ? $_POST['ios_msg_fcm_regid'] : '';

    $gcm_regId = helper::clearText($gcm_regId);
    $gcm_regId = helper::escapeText($gcm_regId);

    $ios_fcm_regId = helper::clearText($ios_fcm_regId);
    $ios_fcm_regId = helper::escapeText($ios_fcm_regId);

    $android_msg_fcm_regid = helper::clearText($android_msg_fcm_regid);
    $android_msg_fcm_regid = helper::escapeText($android_msg_fcm_regid);

    $ios_msg_fcm_regid = helper::clearText($ios_msg_fcm_regid);
    $ios_msg_fcm_regid = helper::escapeText($ios_msg_fcm_regid);

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $result = array("error" => true);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new account($dbo, $accountId);
    $account->setLastActive();

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS,
                    "accessToken" => $accessToken,
                    "accountId" => $accountId,
                    "account" => array());

    if (strlen($gcm_regId) != 0) {

        $account->setGCM_regId($gcm_regId);
    }

    if (strlen($ios_fcm_regId) != 0) {

        $account->set_ios_fcm_regId($ios_fcm_regId);
    }

    if (strlen($ios_msg_fcm_regid) != 0) {

        $account->set_ios_msg_fcm_regId($ios_msg_fcm_regid);
    }

    if (strlen($android_msg_fcm_regid) != 0) {

        $account->set_android_msg_fcm_regId($android_msg_fcm_regid);
    }

    array_push($result['account'], $account->get());

    echo json_encode($result);
    exit;
}
