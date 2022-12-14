<?php
class securityService {
    private $formTokenLabel = 'eg-csrf-token-label';
    private $sessionTokenLabel = 'EG_CSRF_TOKEN_SESS_IDX';
    private $post = array();
    private $session = array();
    private $server = array();
    private $excludeUrl = array();
    private $hashAlgo = 'sha256';
    private $hmac_ip = true;
    private $hmacData = 'a0a47d5fce67821fd8eb63b869286272';
  
    public function __construct($excludeUrl = null, &$post = null, &$session = null, &$server = null)
    {
        if (! \is_null($excludeUrl)) {
            $this->excludeUrl = $excludeUrl;
        }
        if (! \is_null($post)) {
            $this->post = & $post;
        } else {
            $this->post = & $_POST;
        }

        if (! \is_null($server)) {
            $this->server = & $server;
        } else {
            $this->server = & $_SERVER;
        }

        if (! \is_null($session)) {
            $this->session = & $session;
        } elseif (! \is_null($_SESSION) && isset($_SESSION)) {
            $this->session = & $_SESSION;
        } else {
            //throw new \Error('No session available for persistence');
        }
    }

    public function validate()
    {
      $currentUrl = $this->getCurrentRequestUrl();
      if (! in_array($currentUrl, $this->excludeUrl)) {
            if (! empty($this->post)) {
                $isAntiCSRF = $this->validateRequest();
                if (! $isAntiCSRF) {
                    return false;
                }
                return true;
            }
        }
    }

    private function validateRequest()
    {
        if (! isset($this->session[$this->sessionTokenLabel])) {
            return false;
        }

        if (! empty($this->post[$this->formTokenLabel])) {
            $token = $this->post[$this->formTokenLabel];
        } else {
            return false;
        }

        if (! \is_string($token)) {
            return false;
        }

        if ($this->hmac_ip !== false) {
            $expected = $this->hMacWithIp($this->session[$this->sessionTokenLabel]);
        } else {
            $expected = $this->session[$this->sessionTokenLabel];
        }

        return \hash_equals($token, $expected);
    }

    private function getCurrentRequestUrl()
    {
        $protocol = "http";
        if (isset($this->server['HTTPS'])) {
            $protocol = "https";
        }
        $currentUrl = $protocol . "://" . $this->server['HTTP_HOST'] . $this->server['REQUEST_URI'];
        return $currentUrl;
    }

    public function insertHiddenToken()
    {
        $csrfToken = $this->getCSRFToken();
        
        echo "<input type=\"hidden\"" . " name=\"" . $this->xssafe($this->formTokenLabel) . "\"" . " value=\"" . $this->xssafe($csrfToken) . "\"" . " />";
    }

    private function xssafe($data, $encoding = 'UTF-8')
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

    private function getCSRFToken()
    {
        if (empty($this->session[$this->sessionTokenLabel])) {
            $this->session[$this->sessionTokenLabel] = bin2hex(random_bytes(32));
        }

        if ($this->hmac_ip !== false) {
            $token = $this->hMacWithIp($this->session[$this->sessionTokenLabel]);
        } else {
            $token = $this->session[$this->sessionTokenLabel];
        }
        return $token;
    }

    private function hMacWithIp($token)
    {
        $hashHmac = \hash_hmac($this->hashAlgo, $token, $this->hmacData);
        return $hashHmac;
    }
  }
  ?>