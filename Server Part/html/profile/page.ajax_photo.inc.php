<?php

    /*!
     * ifsoft.co.uk v1.1
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $groupId = $helper->getUserId($request[0]);

    $group = new group($dbo, $groupId);
    $group->setRequestFrom(auth::getCurrentUserId());

    $groupInfo = $group->get();

    if ($groupInfo['accountAuthor'] != auth::getCurrentUserId()) {

        header("Location: /");
        exit;
    }

    if (!empty($_FILES['userfile']['tmp_name'])) {

        $result = array("error" => true);

        if ($_FILES["userfile"]["size"] < 20 * 1024 * 1024) {

            $imglib = new imglib($dbo);
            $result = $imglib->createPhoto($_FILES['userfile']['tmp_name'], $_FILES['userfile']['name']);
            unset($imglib);

            if ($result['error'] === false) {

                $account = new account($dbo, $groupId);
                $account->setPhoto($result);
                unset($account);
            }
        }

        echo json_encode($result);
        exit;
    }

    if (isset($_GET['action'])) {

        $act = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($act) {

            case "get-box": {

                ?>

                    <div class="box-body">
                        <div class="msg" style="margin-top: 0">
                            <?php echo $LANG['label-photo-upload-description']; ?>
                        </div>

                        <div class="file_loader_block" style=""></div>

                        <div class="file_select_block" style="">
                            <div style="" class="file_select_btn cover_input primary_btn green button"><?php echo $LANG['action-select-file-and-upload']; ?></div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="controls">
                            <button onclick="$.colorbox.close(); return false;" class="primary_btn"><?php echo $LANG['action-cancel']; ?></button>
                        </div>
                    </div>

                <?php

                exit;
            }

            default: {

                break;
            }
        }
    }