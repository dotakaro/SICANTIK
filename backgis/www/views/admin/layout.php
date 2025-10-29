<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="<?php echo site_url('assets/images/icon/favicon.ico');?>">
    <?php echo $template['partials']['header']; ?>
</head>
    <body>
        <div id="main">
            <div class="container">
                <?php echo $template['partials']['title']; ?>
                <?php echo $template['partials']['navigation']; ?>
                <?php
                $flashMessage = $this->session->flashdata('flash_message');
                if(!empty($flashMessage)) {
                    $message = array();
                    if (is_array($flashMessage)) {
                        $message = $flashMessage;
                        $iconHtml = "";
                        switch (strtolower($flashMessage['class'])) {
                            case 'error':
                                $message['class'] = 'flash-error';
//                                $iconHtml = img('assets/images/icon/tick.png')."&nbsp;";
                                break;
                            case 'warning':
                                $message['class'] = 'flash-warning';
//                                $iconHtml = img('assets/images/icon/tick.png')."&nbsp;";
                                break;
                            default:
                                $message['class'] = 'flash-success';
//                                $iconHtml = img('assets/images/icon/tick.png')."&nbsp;";
                                break;
                        }
                        $message['message'] = $iconHtml.$flashMessage['message'];
                    } else {
                        $message['class'] = 'flash-success';
                        $message['message'] = $flashMessage;
                    }
                    echo "<div style=\"clear:both\" class=\"flash-message {$message['class']}\">".
                            "{$message['message']}".
                            "<span id=\"flash-close-btn\">".img('assets/images/icon/cross.png')."</span>".
                        "</div>";
                }
                ?>
                <?php echo $template['body']; ?>
            </div>
            <?php echo $template['partials']['footer']; ?>
        </div>
    </body>
</html>