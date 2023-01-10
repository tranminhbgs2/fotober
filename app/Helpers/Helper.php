<?php

use App\Helpers\Constants;
use App\Models\LogActionModel;
use App\Models\LogLoginModel;
use Illuminate\Support\Facades\Auth;

if (!function_exists('setActiveMenu')) {
    function setActiveMenu($route)
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (\Illuminate\Support\Facades\Route::currentRouteName() == $r) {
                    return 'active';
                }
            }

            return '';
        }

        return Route::currentRouteName() == $route ? 'active' : '';
    }
};

if (!function_exists('includeRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('saveLogToFile')) {
    function saveLogToFile($content)
    {
        if (!in_array(gettype($content), ['String', 'string'])) {
            $content = json_encode($content);
        }
        $content = PHP_EOL . date('d/m/Y H:i:s') . PHP_EOL . $content . PHP_EOL;
        $fields_file = storage_path('app/public/transfers/transfer_log_' . date('Y_m') . '.txt');
        try {
            $file = @fopen($fields_file, "a") or die("Unable to open file!");
            @fclose($file);
            @file_put_contents($fields_file, $content, FILE_APPEND | LOCK_EX);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('addLogAction')) {
    function addLogAction($action, $description, $model, $table, $actor_id = null, $actor_name = null, $record_id = null, $ip_address = null)
    {
        LogActionModel::create([
            'actor_id' => $actor_id,
            'actor_name' => $actor_name,
            'action' => $action,
            'description' => $description,
            'model' => $model,
            'table' => $table,
            'record_id' => $record_id,
            'ip_address' => $ip_address
        ]);
        return true;
    }
}

if (!function_exists('addLogLogin')) {
    function addLogLogin($action, $account_input, $result, $time_login = null, $time_logout = null, $user_agent = null, $ip_address = null)
    {
        LogLoginModel::create([
            'action' => $action,
            'time_login' => $time_login,
            'account_input' => $account_input,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'type' => 'BE',
            'result' => $result,
            'time_logout' => $time_logout
        ]);
        return true;
    }
}

if (!function_exists('translateKeyWord')) {
    function translateKeyWord($keyWord)
    {
        if (empty($keyWord)) {
            return $keyWord;
        } else {
            return str_replace(['%'], ['\%'], $keyWord);
        }
    }
}

if (!function_exists('ci_random_string')) {
    /**
     * Create a "Random" String
     *
     * @param string    type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
     * @param int    number of characters
     * @return    string
     */
    function ci_random_string($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique': // todo: remove in 3.1+
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt': // todo: remove in 3.1+
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }
}

if (!function_exists('getEmailBody')) {
    /**
     * Hàm lấy body để gửi mail cho admin và khách hàng
     *
     * @param string $type
     * @param array $params
     *
     * @return string
     */
    function getEmailBody($type = 'FORGOT_PASSWORD', $params = array())
    {
        $body = '';
        $email = isset($params['email']) ? $params['email'] : null;
        $fullname = isset($params['fullname']) ? $params['fullname'] : $email;
        $title = isset($params['title']) ? $params['title'] : null;
        $mobile = isset($params['mobile']) ? $params['mobile'] : null;
        $content = isset($params['content']) ? $params['content'] : null;
        $address = isset($params['address']) ? $params['address'] : null;
        $company_name = isset($params['company_name']) ? $params['company_name'] : null;
        $city = isset($params['city']) ? $params['city'] : null;
        $url = isset($params['url']) ? $params['url'] : null;
        $order = isset($params['order']) ? $params['order'] : null;
        $subject = isset($params['subject']) ? $params['subject'] : null;
        $created_at = isset($order->created_at) ? $order->created_at : date('Y-m-d H:i:s');

        switch ($type) {
            case Constants::EMAIL_ORDER_CREATE:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Job Created</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p><br>'.
                                            '<p>Order ID ' . optional($order)->code . ' has been submitted successfully: ' . date('d/m/Y', strtotime($created_at)).'</p>'.
                                            "<p>We've got your order with below information:</p>".
                                            '<p>Order Name: ' . optional($order)->name . '</p>
                                            <p>Order Code: ' . optional($order)->code . '</p>
                                            <p>Order Date: ' . date('d/m/Y', strtotime($created_at)) . '</p>
                                            <p>Job Requirement: ' . optional($order)->notes . '</p>
                                            <p>Your order is being proceeded by our team. Thank you for allowing Fotober to serve you.</p>'.
                                            "<p>If you've got any questions please don't hesitate to get in touch with us via e-mail support@fotober.com</p>".
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            case Constants::EMAIL_ORDER_DELIVERY:
            case Constants::EMAIL_ORDER_COMPLETED:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Done Job</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p>'.
                                            '<br>'.
                                            '<p>We would like to inform you that your order has been Completed. Please kindly check the project for the outputs. If you have any additional requirements or feedback, just feel free to let us know.</p>'.
                                            '<p style="text-align: center"><a href="'. Constants::CRM_DOMAIN .'" target="_blank"><button style="background-color: #1dabe3; color: white; padding: 10px 15px; border: none">View Project</button></a></p>'.
                                            '<br>'.
                                            "<p>Thank you for using our services.</p>".
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            case Constants::EMAIL_ACCOUNT_ACTIVATION:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Welcome to Fotober.com</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                        <p>Dear <strong>' . $fullname . '</strong>, welcome to Fotober.com!</p>
                                        <p>Your Fotober login is: ' . $email . '</p>
                                        <p>Your password as in sign-up.</p>
                                        <p>Please click this link to confirm your email at Fotober.com: <a target="_blank" href="'.$url.'">' . $url . '</a></p>
                                        <p>To submit your photos for retouching, please login at Fotober.com and click "Create Order"</p>
                                        <br>
                                        <p>All the best,</p>
                                        <p>Fotober.com</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            case Constants::ORDER_UPDATE_REVISION:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">The revision has been corrected and re-uploaded</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p><br>'.
                                            '<p>Order ID ' . optional($order)->code . ' has been updated.</p>'.
                                            "<p>Please kindly check the output and leave your feedback on our website.</p>".
                                            "<br>
                                            <p>If you've got any questions please don't hesitate to get in touch with us via e-mail support@fotober.com</p>".
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            
            case Constants::EMAIL_ORDER_ASSIGN_SALE:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Job Created</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p><br>'.
                                            '<p>Order ID ' . optional($order)->code . ' has been assigned for you: ' . date('d/m/Y', strtotime($created_at)).'</p>'.
                                            "<p>We've got your order with below information:</p>".
                                            '<p>Order Name: ' . optional($order)->name . '</p>
                                            <p>Order Code: ' . optional($order)->code . '</p>
                                            <p>Order Date: ' . date('d/m/Y', strtotime($created_at)) . '</p>
                                            <p>Job Requirement: ' . optional($order)->notes . '</p>
                                            <p>Your order is being proceeded by our team. Thank you for allowing Fotober to serve you.</p>'.
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            case Constants::EMAIL_ORDER_REQUEST_OUTPUT:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Job Created</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p><br>'.
                                            '<p>Order ID ' . optional($order)->code . ' has been requested to edit the output: ' . date('d/m/Y', strtotime($created_at)).'</p>'.
                                            "<p>We've got your order with below information:</p>".
                                            '<p>Order Name: ' . optional($order)->name . '</p>
                                            <p>Order Code: ' . optional($order)->code . '</p>
                                            <p>Order Date: ' . date('d/m/Y', strtotime($created_at)) . '</p>
                                            <p>Job Requirement: ' . optional($order)->notes . '</p>
                                            <p>Your order is being proceeded by our team. Thank you for allowing Fotober to serve you.</p>'.
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            case Constants::EMAIL_ORDER_ACCEPT_OUTPUT:
                $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Job Created</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                            <p>Hi , <strong>' . $fullname . '</strong>!</p><br>'.
                                            '<p>Order ID ' . optional($order)->code . ' has been accepted output: ' . date('d/m/Y', strtotime($created_at)).'</p>'.
                                            "<p>We've got your order with below information:</p>".
                                            '<p>Order Name: ' . optional($order)->name . '</p>
                                            <p>Order Code: ' . optional($order)->code . '</p>
                                            <p>Order Date: ' . date('d/m/Y', strtotime($created_at)) . '</p>
                                            <p>Job Requirement: ' . optional($order)->notes . '</p>
                                            <p>Your order is being proceeded by our team. Thank you for allowing Fotober to serve you.</p>'.
                                            '<br>
                                            <p>Kind Regards,</p>
                                            <p>Fotober</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="yj6qo"></div>
                    <div class="adL"></div>
                </div>
                <div style ="display:none"><img src=""></div>';
                break;
            default:
            $body = '<div bgcolor="#F1F1F1" style="min-width:100%!important;margin:40px 0;padding:40px 0;background:#f1f1f1;font-size:13px;font-family:\'Helvetica\',\'Arial\'">
                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F1F1" style="background:#f1f1f1;width:100%;height:100%;font-size:14px;line-height:1.5;border-collapse:collapse">
                    <tbody>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
                                <tbody>
                                <tr>
                                    <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Fotober - Thông báo</td>
                                </tr>
                                <tr>
                                    <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                                        <p>Xin chào, <strong>' . $fullname . '</strong>!</p>
                                        <p>' . $subject . '</p>
                                        <p>Thông tin đơn hàng:</p>
                                        <p>- Tên đơn hàng: ' . optional($order)->name . '</p>
                                        <p>- Mã đơn hàng: ' . optional($order)->code . '</p>
                                        <p>- Ghi chú: ' . optional($order)->notes . '</p>
                                        <br>
                                        <p>Trân trọng thông báo!</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
                            <br>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="yj6qo"></div>
                <div class="adL"></div>
            </div>
            <div style ="display:none"><img src=""></div>';
        }
        return $body;
    }
}

if (!function_exists('validateMobile')) {
    function validateMobile($mobile)
    {
        if ($mobile == "" || $mobile == null) return false;

        $start_pattern = "/";
        $end_pattern = "/";

        $viettel_pattern = $start_pattern;

        $viettel_pattern .= "^8498\d{7}$|^0?98\d{7}$|^98\d{7}$";
        $viettel_pattern .= "|^8497\d{7}$|^0?97\d{7}$|^97\d{7}$";
        $viettel_pattern .= "|^8496\d{7}$|^0?96\d{7}$|^96\d{7}$";
        $viettel_pattern .= "|^8432\d{7}$|^0?32\d{7}$|^32\d{7}$";
        $viettel_pattern .= "|^8433\d{7}$|^0?33\d{7}$|^33\d{7}$";
        $viettel_pattern .= "|^8434\d{7}$|^0?34\d{7}$|^34\d{7}$";
        $viettel_pattern .= "|^8435\d{7}$|^0?35\d{7}$|^35\d{7}$";
        $viettel_pattern .= "|^8436\d{7}$|^0?36\d{7}$|^36\d{7}$";
        $viettel_pattern .= "|^8437\d{7}$|^0?37\d{7}$|^37\d{7}$";
        $viettel_pattern .= "|^8438\d{7}$|^0?38\d{7}$|^38\d{7}$";
        $viettel_pattern .= "|^8439\d{7}$|^0?39\d{7}$|^39\d{7}$";
        $viettel_pattern .= "|^8486\d{7}$|^0?86\d{7}$|^86\d{7}$";

        $vinaphone_pattern = $viettel_pattern;
        $vinaphone_pattern .= "|^8491\d{7}$|^0?91\d{7}$|^91\d{7}$";
        $vinaphone_pattern .= "|^8494\d{7}$|^0?94\d{7}$|^94\d{7}$";
        $vinaphone_pattern .= "|^8481\d{7}$|^0?81\d{7}$|^81\d{7}$";
        $vinaphone_pattern .= "|^8482\d{7}$|^0?82\d{7}$|^82\d{7}$";
        $vinaphone_pattern .= "|^8483\d{7}$|^0?83\d{7}$|^83\d{7}$";
        $vinaphone_pattern .= "|^8484\d{7}$|^0?84\d{7}$|^84\d{7}$";
        $vinaphone_pattern .= "|^8485\d{7}$|^0?85\d{7}$|^85\d{7}$";
        $vinaphone_pattern .= "|^8488\d{7}$|^0?88\d{7}$|^88\d{7}$";

        $mobifone_pattern = $vinaphone_pattern;
        $mobifone_pattern .= "|^8490\d{7}$|^0?90\d{7}$|^90\d{7}$";
        $mobifone_pattern .= "|^8493\d{7}$|^0?93\d{7}$|^93\d{7}$";
        $mobifone_pattern .= "|^8470\d{7}$|^0?70\d{7}$|^70\d{7}$";
        $mobifone_pattern .= "|^8476\d{7}$|^0?76\d{7}$|^76\d{7}$";
        $mobifone_pattern .= "|^8477\d{7}$|^0?77\d{7}$|^77\d{7}$";
        $mobifone_pattern .= "|^8478\d{7}$|^0?78\d{7}$|^78\d{7}$";
        $mobifone_pattern .= "|^8479\d{7}$|^0?79\d{7}$|^79\d{7}$";
        $mobifone_pattern .= "|^8489\d{7}$|^0?89\d{7}$|^89\d{7}$";

        $vietnamobile_pattern = $mobifone_pattern;
        $vietnamobile_pattern .= "|^8492\d{7}$|^0?92\d{7}$|^92\d{7}$";
        $vietnamobile_pattern .= "|^8456\d{7}$|^0?56\d{7}$|^56\d{7}$";
        $vietnamobile_pattern .= "|^8458\d{7}$|^0?58\d{7}$|^58\d{7}$";

        $vietnamobile_pattern .= $end_pattern;

        // $landline_pattern = /^84203\d{8}$|^0?203\d{8}$|^203\d{8}$|^84204\d{8}$|^0?204\d{8}$|^204\d{8}$|^84205\d{8}$|^0?205\d{8}$|^205\d{8}$|^84206\d{8}$|^0?206\d{8}$|^206\d{8}$|^84207\d{8}$|^0?207\d{8}$|^207\d{8}$|^84208\d{8}$|^0?208\d{8}$|^208\d{8}$|^84209\d{8}$|^0?209\d{8}$|^209\d{8}$|^84210\d{8}$|^0?210\d{8}$|^210\d{8}$|^84211\d{8}$|^0?211\d{8}$|^211\d{8}$|^84212\d{8}$|^0?212\d{8}$|^212\d{8}$|^84213\d{8}$|^0?213\d{8}$|^213\d{8}$|^84214\d{8}$|^0?214\d{8}$|^214\d{8}$|^84215\d{8}$|^0?215\d{8}$|^215\d{8}$|^84216\d{8}$|^0?216\d{8}$|^216\d{8}$|^84218\d{8}$|^0?218\d{8}$|^218\d{8}$|^84219\d{8}$|^0?219\d{8}$|^219\d{8}$|^84220\d{8}$|^0?220\d{8}$|^220\d{8}$|^84221\d{8}$|^0?221\d{8}$|^221\d{8}$|^84222\d{8}$|^0?222\d{8}$|^222\d{8}$|^84225\d{8}$|^0?225\d{8}$|^225\d{8}$|^84226\d{8}$|^0?226\d{8}$|^226\d{8}$|^84227\d{8}$|^0?227\d{8}$|^227\d{8}$|^84228\d{8}$|^0?228\d{8}$|^228\d{8}$|^84229\d{8}$|^0?229\d{8}$|^229\d{8}$|^84232\d{8}$|^0?232\d{8}$|^232\d{8}$|^84233\d{8}$|^0?233\d{8}$|^233\d{8}$|^84234\d{8}$|^0?234\d{8}$|^234\d{8}$|^84235\d{8}$|^0?235\d{8}$|^235\d{8}$|^84236\d{8}$|^0?236\d{8}$|^236\d{8}$|^84237\d{8}$|^0?237\d{8}$|^237\d{8}$|^84238\d{8}$|^0?238\d{8}$|^238\d{8}$|^84239\d{8}$|^0?239\d{8}$|^239\d{8}$|^8424\d{8}$|^0?24\d{8}$|^24\d{8}$|^84251\d{8}$|^0?251\d{8}$|^251\d{8}$|^84252\d{8}$|^0?252\d{8}$|^252\d{8}$|^84254\d{8}$|^0?254\d{8}$|^254\d{8}$|^84255\d{8}$|^0?255\d{8}$|^255\d{8}$|^84256\d{8}$|^0?256\d{8}$|^256\d{8}$|^84257\d{8}$|^0?257\d{8}$|^257\d{8}$|^84258\d{8}$|^0?258\d{8}$|^258\d{8}$|^84259\d{8}$|^0?259\d{8}$|^259\d{8}$|^84260\d{8}$|^0?260\d{8}$|^260\d{8}$|^84261\d{8}$|^0?261\d{8}$|^261\d{8}$|^84262\d{8}$|^0?262\d{8}$|^262\d{8}$|^84263\d{8}$|^0?263\d{8}$|^263\d{8}$|^84269\d{8}$|^0?269\d{8}$|^269\d{8}$|^84270\d{8}$|^0?270\d{8}$|^270\d{8}$|^84271\d{8}$|^0?271\d{8}$|^271\d{8}$|^84272\d{8}$|^0?272\d{8}$|^272\d{8}$|^84273\d{8}$|^0?273\d{8}$|^273\d{8}$|^84274\d{8}$|^0?274\d{8}$|^274\d{8}$|^84275\d{8}$|^0?275\d{8}$|^275\d{8}$|^84276\d{8}$|^0?276\d{8}$|^276\d{8}$|^84277\d{8}$|^0?277\d{8}$|^277\d{8}$|^8428\d{8}$|^0?28\d{8}$|^28\d{8}$|^84290\d{8}$|^0?290\d{8}$|^290\d{8}$|^84291\d{8}$|^0?291\d{8}$|^291\d{8}$|^84292\d{8}$|^0?292\d{8}$|^292\d{8}$|^84293\d{8}$|^0?293\d{8}$|^293\d{8}$|^84294\d{8}$|^0?294\d{8}$|^294\d{8}$|^84296\d{8}$|^0?296\d{8}$|^296\d{8}$|^84297\d{8}$|^0?297\d{8}$|^297\d{8}$|^84299\d{8}$|^0?299\d{8}$|^299\d{8}$/;

        if (preg_match($vietnamobile_pattern, $mobile)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('formatMobile')) {
    function formatMobile($mobile)
    {
        $res_format = '';
        if (validateMobile($mobile)) {
            switch (strlen($mobile)) {
                case 9:
                    $res_format = '84' . $mobile;
                    break;
                case 10:
                    $res_format = '84' . substr($mobile, 1);
                    break;
                case 11:
                    $res_format = $mobile;
                default:
            }
        }

        return $res_format;
    }
}

if (!function_exists('getNameFromEmail')) {
    /**
     * Hàm tách lấy phần trước @ từ email
     *
     * @param $email
     * @return false|string|null
     */
    function getNameFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return substr($email, 0, strpos($email, '@'));
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('getDom')) {
    function getDom($link)
    {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $dom = \Sunra\PhpSimple\HtmlDomParser::str_get_html($content);

        return $dom;
    }
}

if (!function_exists('cURL')) {
    function cURL($url, $jwt = null, $params = [])
    {
        $ch = curl_init();
        //
        //$headers = array();
        //$headers[] = 'Authority: mp3.zing.vn';
        //$headers[] = 'Cache-Control: max-age=0';
        //$headers[] = 'Upgrade-Insecure-Requests: 1';
        //$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';
        //$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
        //$headers[] = 'Accept-Encoding: gzip, deflate, br';
        //$headers[] = 'Accept-Language: vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7,ja;q=0.6';
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($jwt) {
            $headers = array();
            $headers[] = 'Authorization: Bearer ' . $jwt;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if (isset($params['authority']) && $params['authority'] == 'ZALO.AI' && isset($params['apikey']) && $params['apikey']) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . $params['apikey']
            ]);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (isset($params['method']) && strtoupper($params['method']) == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');


        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return $result;
    }
}

if (!function_exists('getTimeAgo')) {
    function getTimeAgo($date)
    {
        $date = str_replace('/', '-', $date);
        $text = 'N/A';

        if ($date) {
            $temp = time() - strtotime($date);
            if ($temp >= 0) {
                $str = 'trước';
            } else {
                $str = 'nữa';
            }
            $temp = abs($temp);

            if (0 <= $temp && $temp < 60) {
                $text = 'Vừa xong';
            } elseif (60 <= $temp && $temp < 3600) {
                $text = floor($temp / 60) . ' phút ' . $str;
            } elseif (3600 <= $temp && $temp < 86400) {
                $text = floor($temp / 3600) . ' giờ ' . $str;
            } elseif (86400 <= $temp && $temp < 604800) {
                $text = floor($temp / 86400) . ' ngày ' . $str;
            } elseif (604800 <= $temp && $temp < 2592000) {
                $text = floor($temp / 604800) . ' tuần ' . $str;
            } elseif (2592000 <= $temp && $temp < 31104000) {
                $text = floor($temp / 2592000) . ' tháng ' . $str;
            } else {
                $text = floor($temp / 31104000) . ' năm ' . $str;
            }
        }

        return $text;

    }
}

if (!function_exists('getImageLoadingBase64')) {
    function getImageLoadingBase64()
    {
        return 'data:image/gif;base64,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';
    }
}

if (!function_exists('getImagePath')) {
    function getImagePath($url)
    {
        if (strpos($url, 'uploads/') !== false) {
            return asset('storage/' . $url);
        } else {
            return 'http://orders.fotober.com' . $url;
        }
    }
}

if (!function_exists('format_yyyymmddhhiiss')) {
    function format_yyyymmddhhiiss($strtime, $separate = '/')
    {
        $strtime = preg_replace("/[^0-9]/", "", $strtime);
        if (strlen($strtime) == 14) {
            return preg_replace(
                "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
                "$3$separate$2$separate$1 $4:$5",
                $strtime
            );
        } else {
            return $strtime;
        }
    }
}

if (!function_exists('translateKeyWord')) {
    function translateKeyWord($keyWord)
    {
        if (empty($keyWord)) {
            return $keyWord;
        } else {
            return str_replace(['%'], ['\%'], $keyWord);
        }
    }
}

if (!function_exists('statusUser')) {
    function statusUser($status)
    {
        $name = '';
        switch ($status) {
            case 0:
                $name = 'Chưa kích hoạt';
                break;
            case 1:
                $name = 'Đã kích hoạt';
                break;
            case 2:
                $name = 'Đang xác minh';
                break;
            case 3:
                $name = 'Xác minh thành công';
                break;

            case 4:
                $name = 'Xác minh lại';
                break;
            case 5:
                $name = 'Chờ xác minh mở tài khoản CQG';
                break;

            case 6:
                $name = 'Mở tài khoản CQG thành công';
                break;

            case 7:
                $name = 'Chờ xác minh thay đổi gói phí CQG';
                break;

            case 8:
                $name = 'Đóng tài khoản CQG';
                break;

            case 9:
                $name = 'Tạm khóa';
                break;

            case 10:
                $name = 'Xóa';
                break;

            default:
                $name = 'Tạm khóa';
        }
        return $name;
    }
}

if (!function_exists('sendRequest')) {
    /**
     * Hàm xử lý request
     *
     * @param $url
     * @param array $params
     * @param string $method
     * @param bool $isJSON
     * @param bool $isAuthen
     * @param null $bearerToken
     * @param int $timeOut
     * @return mixed
     */
    function sendRequest($url, $params = array(), $method = 'POST', $isJSON = true, $isAuthen = true, $bearerToken = null, $timeOut = 15, $status = false)
    {
        $request = \Ixudra\Curl\Facades\Curl::to($url)
            ->withData($params)
            ->withOption('TIMEOUT', $timeOut)
            ->withOption('CONNECTTIMEOUT', 0)
            ->withOption('SSL_VERIFYPEER', 0)
            ->withContentType('application/json')
            ->withOption('FOLLOWLOCATION', true)
            ->returnResponseObject();

        if ($isJSON) {
            $request->asJsonRequest();
        }

        if ($isAuthen) {
            $request->withOption('USERPWD', 'admin:weppoHER4352GGErfg');
        }

        if ($bearerToken) {
            $request->withBearer($bearerToken);
        }

        $response = '';
        switch ($method) {
            case 'GET':
                $response = $request->get();
                break;
            case 'POST':
                $response = $request->post();
                break;
            case 'PUT':
                $response = $request->put();
                break;
            case 'PATCH':
                $response = $request->patch();
                break;
            case 'DELETE':
                $response = $request->delete();
                break;
            default:
                break;
        }
        if($status){
            return $response;
        } else{
            return $response->content;
        }
    }
}

if (!function_exists('getImageLoadingBase64')) {
    function getImageLoadingBase64()
    {
        return 'data:image/gif;base64,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';
    }
}

if (!function_exists('getOrderStatus')) {
    function getOrderStatus($status=null, $account_type=null)
    {
        $data = [
            0 => trans('fotober.order.status_0'),
            1 => trans('fotober.order.status_1'),
            2 => trans('fotober.order.status_2'),
            3 => trans('fotober.order.status_3'),
            4 => trans('fotober.order.status_4'),
            5 => trans('fotober.order.status_5'),
            6 => trans('fotober.order.status_6'),
            7 => trans('fotober.order.status_7'),
            8 => trans('fotober.order.status_8'),
            9 => trans('fotober.order.status_9'),
            10 => trans('fotober.order.status_10'),
            11 => trans('fotober.order.status_11'),
            12 => trans('fotober.order.status_12'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        if ($account_type) {
            switch ($account_type) {
                case Constants::ACCOUNT_TYPE_CUSTOMER:
                    $array_status = [
                        0 => trans('fotober.order.status_0'),
                        1 => trans('fotober.order.status_1'),
                        2 => trans('fotober.order.status_2'),
                        //Start Editing
                        3 => trans('fotober.order.status_3'),
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        //End Editing
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                        10 => trans('fotober.order.status_10'),
                        11 => trans('fotober.order.status_11'),
                    ];
                    break;
                case Constants::ACCOUNT_TYPE_SALE:
                    $array_status = [
                        1 => trans('fotober.order.status_1'),
                        2 => trans('fotober.order.status_2'),
                        3 => trans('fotober.order.status_3'),
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                        10 => trans('fotober.order.status_10'),
                        11 => trans('fotober.order.status_11'),
                    ];
                    break;
                case Constants::ACCOUNT_TYPE_ADMIN:
                    $array_status = [
                        2 => trans('fotober.order.status_2'),
                        3 => trans('fotober.order.status_3'),
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                    ];
                    break;
                case Constants::ACCOUNT_TYPE_EDITOR:
                    $array_status = [
                        3 => trans('fotober.order.status_3'),
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                    ];
                    break;
                case Constants::ACCOUNT_TYPE_QAQC:
                    $array_status = [
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                    ];
                    break;
                case Constants::ACCOUNT_TYPE_SUPER_ADMIN:
                    $array_status = [
                        0 => trans('fotober.order.status_0'),
                        1 => trans('fotober.order.status_1'),
                        2 => trans('fotober.order.status_2'),
                        3 => trans('fotober.order.status_3'),
                        //4 => trans('fotober.order.status_4'),
                        //5 => trans('fotober.order.status_5'),
                        //6 => trans('fotober.order.status_6'),
                        //7 => trans('fotober.order.status_7'),
                        8 => trans('fotober.order.status_8'),
                        9 => trans('fotober.order.status_9'),
                        10 => trans('fotober.order.status_10'),
                        11 => trans('fotober.order.status_11'),
                        12 => trans('fotober.order.status_12'),
                    ];
                    break;
                default:
                    $array_status = [];
            }
        } else {
            $array_status = [];
        }

        return $array_status;

    }
}

if (!function_exists('getPaymentStatus')) {
    function getPaymentStatus($status = null)
    {
        $data = [
            0 => trans('fotober.payment.status_0'),
            1 => trans('fotober.payment.status_1'),
            2 => trans('fotober.payment.status_2'),
            3 => trans('fotober.payment.status_3'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        return $data;

    }
}

if (!function_exists('getRequirementStatus')) {
    function getRequirementStatus($status = null)
    {
        $data = [
            0 => trans('fotober.requirement.status_0'),
            1 => trans('fotober.requirement.status_1'),
            2 => trans('fotober.requirement.status_2'),
            3 => trans('fotober.requirement.status_3'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        return $data;

    }
}

if (!function_exists('getCustomerStatus')) {
    function getCustomerStatus($status = null)
    {
        $data = [
            0 => trans('fotober.customer.status_0'),
            1 => trans('fotober.customer.status_1'),
            2 => trans('fotober.customer.status_2'),
            3 => trans('fotober.customer.status_3'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        return $data;

    }
}

if (!function_exists('getGroupStatus')) {
    function getGroupStatus($status = null)
    {
        $data = [
            0 => trans('fotober.customer.status_0'),
            1 => trans('fotober.customer.status_1'),
            2 => trans('fotober.customer.status_2'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        return $data;

    }
}

if (!function_exists('getServiceStatus')) {
    function getServiceStatus($status = null)
    {
        $data = [
            0 => trans('fotober.customer.status_0'),
            1 => trans('fotober.customer.status_1'),
            2 => trans('fotober.customer.status_2'),
        ];

        if (array_key_exists($status, $data)) {
            return $data[$status];
        }

        return $data;

    }
}

if (!function_exists('getPageSizeShow')) {
    function getPageSizeShow($key = null)
    {
        $data = [
            5 => 5,
            10 => 10,
            15 => 15,
            20 => 20,
            25 => 25,
            30 => 30,
            50 => 50,
            100 => 100,
        ];

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $data;

    }
}

if (!function_exists('getTurnArroundTime')) {
    function getTurnArroundTime($key = null)
    {
        $data = [
            12 => '12h',
            24 => '24h (1d)',
            36 => '36h (1.5d)',
            48 => '48h (2d)',
            60 => '60h (2.5d)',
            72 => '72h (3d)',
            84 => '84h (3.5d)',
            96 => '96h (4d)',
            108 => '108h (4.5d)',
            120 => '120h (5d)',
            132 => '132h (5.5d)',
            144 => '144h (6d)',
            156 => '156h (6.5d)',
            168 => '168h (7d)',
            180 => '180h (7.5d)',
            192 => '192h (8d)',
            204 => '204h (8.5d)',
            216 => '216h (9d)',
            228 => '228h (9.5d)',
            240 => '240h (10d)',
            252 => '252h (10.5d)',
            264 => '264h (11d)',
            276 => '276h (11.5d)',
            288 => '288h (12d)',
            300 => '300h (12.5d)',
            312 => '312h (13d)',
            324 => '324h 13.5d)',
            336 => '336h (14d)'
        ];

        if ($key != null && $key >= 0) {
            if (array_key_exists($key, $data)) {
                return $data[$key];
            } else {
                return '';
            }
        }

        return $data;

    }
}

if (!function_exists('getBladeFromLayout')) {
    function getBladeFromLayout($blade_path = null)
    {
        return Constants::VIEW_LAYOUT_PATH . $blade_path;
    }
}

if (!function_exists('generateOrderLinkByAccount')) {
    /**
     * Hàm tạo link chi tiết khi nhấn vào thông báo, tùy thuộc vào quyền
     *
     * @param $account_type
     * @param $order_id
     * @return string
     */
    function generateOrderLinkByAccount($account_type, $order_id, $noti_id=null) {
        $link = '';
        if ($account_type && $order_id > 0) {
            switch ($account_type) {
                case Constants::ACCOUNT_TYPE_CUSTOMER:
                    if ($noti_id > 0) {                        
                        $link = route('customer_order_preview', ['id' => $order_id, 'noti_id' => $noti_id]); break;

                    } else {
                        $link = route('customer_order_preview', ['id' => $order_id]); break;
                    }
                case Constants::ACCOUNT_TYPE_SALE:
                    if ($noti_id > 0) {                        
                        $link = route('sale_order_summary', ['id' => $order_id, 'noti_id' => $noti_id]); break;

                    } else {
                        $link = route('sale_order_summary', ['id' => $order_id ]); break;
                    }
                case Constants::ACCOUNT_TYPE_ADMIN:
                    if ($noti_id > 0) {
                        $link = route('admin_order', ['id' => $order_id, 'noti_id' => $noti_id]); break;
                    } else {
                        $link = route('admin_order', ['id' => $order_id]); break;
                    }
                case Constants::ACCOUNT_TYPE_EDITOR:
                    if ($noti_id > 0) {
                        $link = route('editor_order', ['id' => $order_id, 'noti_id' => $noti_id]); break;
                    } else {
                        $link = route('editor_order', ['id' => $order_id]); break;
                    }
                case Constants::ACCOUNT_TYPE_QAQC:
                    if ($noti_id > 0) {
                        $link = route('qaqc_order', ['id' => $order_id, 'noti_id' => $noti_id]); break;
                    } else {
                        $link = route('qaqc_order', ['id' => $order_id]); break;
                    }
                default: $link = '';
            }
        }

        return $link;
    }
}

if (!function_exists('getBladeFromPage')) {
    function getBladeFromPage($page_blade_path)
    {
        return Constants::VIEW_PAGE_PATH . $page_blade_path;
    }
}

if (!function_exists('paginateAjax')) {
    function paginateAjax($total, $page_index = 1, $page_size = 10, $onclick = 'changePage')
    {
        $link = '';
        $index = 1;
        $btn_next = '>';
        $btn_last = '>|';
        $btn_previous = '<';
        $btn_first = '|<';

        if ($total > 0 && $page_index >= 1 && $page_size >= 1) {
            $pages = ceil($total / $page_size);

            // Previous page
            if ($page_index > 1) {
                $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'(1)">' . $btn_first . '</a>';
                $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.($page_index - 1).')">' . $btn_previous . '</a>';
            }

            if ($pages <= 10) {
                for ($index = 1; $index <= $pages; $index++) {
                    if ($index == $page_index) {
                        $link .= '<span class="pms-page-current">' . $index . '</span>';
                    } else {
                        $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$index.')">' . $index . '</a>';
                    }
                }
            } else {
                if ($page_index <= 5) {
                    for ($index = 1; $index <= 5; $index++) {
                        if ($index == $page_index) {
                            $link .= '<span class="pms-page-current">' . $index . '</span>';
                        } else {
                            $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$index.')">' . $index . '</a>';
                        }
                    }

                    $link .= '...';

                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.($pages - 1).')">' . ($pages - 1) . '</a>';
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$pages.')">' . $pages . '</a>';

                } else if ($page_index > 5 && $page_index < ($pages - 4)) {
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'(1)">1</a>';
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'(2)">2</a>';
                    $link .= '...';

                    for ($index = ($page_index - 2); $index <= ($page_index + 2); $index++) {
                        if ($index == $page_index) {
                            $link .= '<span class="pms-page-current">' . $index . '</span>';
                        } else {
                            $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$index.')">' . $index . '</a>';
                        }
                    }

                    $link .= '...';

                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.($pages - 1).')">' . ($pages - 1) . '</a>';
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$pages.')">' . $pages . '</a>';

                } else {
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'(1)">1</a>';
                    $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'(2)">2</a>';
                    $link .= '...';

                    for ($index = ($pages - 2); $index <= $pages; $index++) {
                        if ($index == $page_index) {
                            $link .= '<span class="pms-page-current">' . $index . '</span>';
                        } else {
                            $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$index.')">' . $index . '</a>';
                        }
                    }
                }
            }

            // Next page
            if ($page_index < $pages) {
                $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.($page_index + 1).')">' . $btn_next . '</a>';
                $link .= '<a class="pms-page" href="javascript:void(0)" onclick="'.$onclick.'('.$pages.')">' . $btn_last . '</a>';
            }

        }

        return $link;
    }
}

if (!function_exists('unsigned')) {
    function unsigned($str, $strtolower = 0)
    {
        $marTViet = array(
            "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
            "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ");
        $marKoDau = array(
            "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d",
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D");
        if ($strtolower != 0) {
            $str = strtolower(str_replace($marTViet, $marKoDau, $str));
        } else {
            $str = str_replace($marTViet, $marKoDau, $str);
        }
        return $str;
    }
}

if (!function_exists('generateOrderCode')) {
    /**
     * Cấu trúc: Quốc Gia + Code KH + Job Code + Job Number + Ngày
     * Vd: AFJ001IE01FEB24 (AF_J001_IE_01_FEB24)
     * @param $country_code
     * @param $cus_code
     * @param $job_code
     * @param $job_number
     * @param $date
     * @return string
     */
    function generateOrderCode($country_code, $cus_code, $job_code, $date)
    {
        if ($country_code && $cus_code && $job_code && $date) {
            switch (strtoupper(trim($job_code))) {
                case 'IE': $job_number = '01'; break;
                case 'VS': $job_number = '02'; break;
                case 'VR': $job_number = '03'; break;
                case 'D2D': $job_number = '04'; break;
                case 'IR': $job_number = '05'; break;
                case 'FPR': $job_number = '06'; break;
                case 'VE': $job_number = '07'; break;
                case '360': $job_number = '08'; break;
                case 'RE': $job_number = '09'; break;
                case 'CJ': $job_number = '10'; break;
                default: $job_number = '11';
            }

            $date = date('Md', strtotime($date));

            return strtoupper($country_code . $cus_code . $job_code . $job_number . $date);
        }

        return strtoupper(uniqid('FTB'));
    }
}

if (!function_exists('getOrderStatusItem')) {
    function getOrderStatusItem($status) {
        $str = '';
        switch ($status) {
            case 0:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Draft</p></li>';
                $str .= '<li><p>Order placed</p></li>';
                $str .= '<li><p>Expert asigned</p></li>';
                $str .= '<li><p>Editing</p></li>';
                $str .= '<li><p>Completed</p></li>';
                $str .= '<li><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 1:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li><p>Expert asigned</p></li>';
                $str .= '<li><p>Editing</p></li>';
                $str .= '<li><p>Completed</p></li>';
                $str .= '<li><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 2:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li><p>Editing</p></li>';
                $str .= '<li><p>Completed</p></li>';
                $str .= '<li><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li class="active"><p>Editing</p></li>';
                $str .= '<li><p>Completed</p></li>';
                $str .= '<li><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 8:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li class="active"><p>Editing</p></li>';
                $str .= '<li class="active"><p>Completed</p></li>';
                $str .= '<li><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 9:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li class="active"><p>Editing</p></li>';
                $str .= '<li class="active"><p>Completed</p></li>';
                $str .= '<li class="active"><p>Re-do</p></li>';
                $str .= '<li><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 10:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li class="active"><p>Editing</p></li>';
                $str .= '<li class="active"><p>Completed</p></li>';
                $str .= '<li class="active"><p>Re-do</p></li>';
                $str .= '<li class="active"><p>Awaiting Payment</p></li>';
                $str .= '<li><p>Paid</p></li>';
                $str .= '</ul>';
                break;
            case 11:
                $str .= '<ul class="time-line">';
                $str .= '<li class="active"><p>Order placed</p></li>';
                $str .= '<li class="active"><p>Expert asigned</p></li>';
                $str .= '<li class="active"><p>Editing</p></li>';
                $str .= '<li class="active"><p>Completed</p></li>';
                $str .= '<li class="active"><p>Re-do</p></li>';
                $str .= '<li class="active"><p>Awaiting Payment</p></li>';
                $str .= '<li class="active"><p>Paid</p></li>';
                $str .= '</ul>';
                break;
        }

        return $str;
    }
}

if (!function_exists('getGroupService')) {
    /**
     * Nhóm dịch vụ
     * 1-Photo Editing, 2-Virtual Staging, 3-Video Editing, 4-Architecture Planning
     * @param null $id
     * @return array|mixed
     */
    function getGroupService($id = null)
    {
        $data = [
            1 => 'Photo Editing',
            2 => 'Virtual Staging',
            3 => 'Video Editing',
            4 => 'Architecture Planning',
            5 => 'Other',
        ];

        if ($id) {
            if (array_key_exists($id, $data)) {
                return $data[$id];
            } else {
                return $data[5];
            }
        }

        return $data;

    }
}

if (!function_exists('getIDService')) {
    /**
     * Nhóm dịch vụ
     * 1-Photo Editing, 2-Virtual Staging, 3-Video Editing, 4-Architecture Planning
     * @param null $id
     * @return array|mixed
     */
    function getIDService($id = null)
    {
        $data = [
            8 => 'Item removal',
            3 => 'Custom job',
            9 => 'Day to dusk',
            7 => 'Floor plan redraw',
            12 => 'Image enhancement',
            6 => 'Video Editing',
            4 => 'Rendering',
            10 => 'Virtual renovation',
            11 => ' Virtual staging',
            5 => '360 Image enhancement',
        ];

        if ($id) {
            switch ($id) {
                case 1:
                    return 12;
                    break;
                case 2:
                    return 11;
                    break;
                case 3:
                    return 10;
                    break;
                case 4:
                    return 9;
                    break;
                
                case 5:
                    return 8;
                    break;
                case 6:
                    return 7;
                    break;
                case 7:
                    return 6;
                    break;
                case 8:
                    return 5;
                    break;
                case 9:
                    return 4;
                    break;
                case 10:
                    return 3;
                    break;
                case 11:
                    return 6;
                    break;
                case 12:
                    return 6;
                    break;
                
                default:
                    return 0;
                    break;
            }
        }
    }
}

if (!function_exists('getServiceType')) {
    /**
     * Loại dịch vụ
     *
     * @param null $id
     * @return array|mixed
     */
    function getServiceType($id = null)
    {
        $data = [
            'BEFORE_AFTER' => trans('fotober.sadmin.service.before_after'),
            'ONLY_VIDEO' => trans('fotober.sadmin.service.only_video'),
            'ONLY_IMAGE' => trans('fotober.sadmin.service.only_image'),
        ];

        if ($id) {
            if (array_key_exists($id, $data)) {
                return $data[$id];
            } else {
                return $data[5];
            }
        }

        return $data;

    }
}

if (!function_exists('getServiceVideoSrc')) {
    /**
     * Bóc tách nguồn video từ url của nó
     * https://www.youtube.com/watch?v=qIIcYuDLUGw (https://www.youtube.com/embed/qIIcYuDLUGw)
     * https://vimeo.com/581952747 (https://player.vimeo.com/video/403530213)
     *
     * @param null $id
     * @return array|mixed
     */
    function getServiceVideoSrc($url)
    {
        $src = Constants::VIDEO_SRC_FOTOBER;

        if (strpos($url, 'https://www.youtube.com/') !== false) {
            $src = Constants::VIDEO_SRC_YOUTUBE;
        } elseif (strpos($url, 'https://player.vimeo.com/video/') !== false) {
            $src = Constants::VIDEO_SRC_VIMEO;
        }

        return $src;
    }
}


?>
