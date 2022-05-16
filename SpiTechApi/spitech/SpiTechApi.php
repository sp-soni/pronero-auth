<?php

class SpiTechApi
{

    private $apiKey = '';
    private $apiServer = '';
    private $logPath = '';
    private $cookiePath = '/';
    private static $singletonObject;
    private final function __construct($params)
    {
        $this->apiServer = 'http://api.spitech.in/';
        $this->logPath = $params['logPath'];
        $this->cookiePath = $params['cookiePath'];
        $this->apiKey = $params['apiKey'];
    }

    public static function getInstance($params)
    {
        if (empty(self::$singletonObject)) {
            self::$singletonObject = new SpiTechApi($params);
        }
        return self::$singletonObject;
    }

    public function setAccountService()
    {
        $url = $this->apiServer . 'api/auth/getAccountService';
        $params = ["api_key" => $this->apiKey];
        $response = $this->executeCurl($url, $params);
        $response = json_decode($response);
        if (!empty($response->data)) {
            $this->setAccountServiceCookie($response->data);
        }
        echo $response->msg;
        return $response;
    }


    public function auth($email, $password)
    {
        $url = $this->apiServer . 'api/auth';
        $params = ["email" => $email, "password" => $password];
        $response = $this->executeCurl($url, $params);
        $response = json_decode($response);
        if (!empty($response->data->token)) {
            $this->setUserCookie($response);
        }
        return $response;
    }

    public function forgotPassword($email)
    {
        $url = $this->apiServer . 'api/auth/forgotPassword';
        $params = ["email" => $email];
        $response = $this->executeCurl($url, $params);
        $response = json_decode($response);
        return $response;
    }

    public function createUser($params)
    {
        $url = $this->apiServer . 'api/accountUser';
        $response = $this->executeCurl($url, $params);
        $response = json_decode($response);
        return $response;
    }

    public function updateUser($api_user_id, $params)
    {
        $url = $this->apiServer . 'api/accountUser/' . $api_user_id;
        $response = $this->executeCurl($url, $params, "PUT");
        $response = json_decode($response);
        return $response;
    }

    public function sendMail($to, $subject, $message)
    {
        $params = array(
            "to" => $to,
            "subject" => $subject,
            "message" => $message
        );
        $url = $this->apiServer . 'api/emailServices/sendmail';
        $response = $this->executeCurl($url, $params);
        $response = json_decode($response);
        return $response;
    }


    // other supportive methods   

    /**
     * Used to call with token
     */
    private function executeCurl($url, $params, $method = "POST")
    {
        try {
            $ch = curl_init();
            $cookie = $this->getUserCookie();
            if (!empty($cookie->token)) {
                $header = array(
                    'Authorization: Bearer ' . $cookie->token,
                    'Content-Type: application/x-www-form-urlencoded'
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            $response = curl_exec($ch);
        } catch (Exception $ex) {
            error_log($ex, $this->logPath . "SpiTechApi.log");
        }
        return $response;
    }

    public function setAccountServiceCookie($accountService)
    {
        $domain = $_SERVER['HTTP_HOST'];
        $avaialble_path = $this->cookiePath;
        $expiry_time = time() + 24 * 3600;  // expires ater 24 hr
        setcookie("SpiTechApi_account_service", json_encode($accountService), $expiry_time, $avaialble_path, $domain, false, true);
    }

    private function setUserCookie($user)
    {
        $domain = $_SERVER['HTTP_HOST'];
        $avaialble_path = $this->cookiePath;
        $expiry_time = time() + 24 * 3600;  // expires ater 24 hr
        setcookie("SpiTechApi_token", json_encode($user->data->token), $expiry_time, $avaialble_path, $domain, false, true);
        setcookie("SpiTechApi_user", json_encode($user->data), $expiry_time, $avaialble_path, $domain, false, true);
    }

    public function getUserCookie()
    {
        $temp = new stdClass();
        if (!empty($_COOKIE["SpiTechApi_token"])) {
            $temp->token = str_replace('"', '', $_COOKIE["SpiTechApi_token"]);
        }

        if (!empty($_COOKIE["SpiTechApi_account_service"])) {
            $account = json_decode($_COOKIE["SpiTechApi_account_service"]);
            $temp->account_id = $account->account_id;
            $temp->service_id = $account->id;
            $temp->login_url = $account->login_url;
            $temp->notice = $account->notice;
            $temp->status = $account->status;
        }

        if (!empty($_COOKIE["SpiTechApi_user"])) {
            $user = json_decode($_COOKIE["SpiTechApi_user"]);
            $temp->email = $user->email;
            $temp->user_id = $user->id;
        }
        return $temp;
    }

    public function destroyUserCookie()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $avaialble_path = $this->cookiePath;
        unset($_COOKIE['SpiTechApi_token']);
        unset($_COOKIE['SpiTechApi_user']);
        $expiry_time = time() - 3600; // destroying by setting back datetime
        setcookie("SpiTechApi_token", '', $expiry_time, $avaialble_path, $domain, false, true);
        setcookie("SpiTechApi_user", '', $expiry_time, $avaialble_path, $domain, false, true);
    }
}
