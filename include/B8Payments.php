<?php
class B8Payments {

	public $payment_type = array(
        "paypal"    => 1,
        "pagseguro" => 2
    );
    
    public $payment_settings = array();
    /*  Paypal:
    
        "sandbox"    => false,
        "user"       => '',
        "pssword"    => '',
        "signature"  => '',
        "currency"   => '',
        "produto"    => '',
        "descricao"  => '',
        "valor"      => '',
        "quantidade" => '',
        "codigo"     => '',
        "returnurl"  => '',
        "cancelurl"  => ''
    */
    
    public function paypal_config (array $requestNvp, $sandbox = false) {

        $apiEndpoint  = 'https://api-3t.' . ($sandbox? 'sandbox.': null);
        $apiEndpoint .= 'paypal.com/nvp';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));

        $response = urldecode(curl_exec($curl));

        curl_close($curl);

        $responseNvp = array();

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }

        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
            for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
                $message = sprintf("PayPal NVP %s[%d]: %s\n",
                                   $responseNvp['L_SEVERITYCODE' . $i],
                                   $responseNvp['L_ERRORCODE' . $i],
                                   $responseNvp['L_LONGMESSAGE' . $i]);

                error_log($message);
            }
        }

        return $responseNvp;        
    }
    
    public function paypal_checkout () {
        $sandbox = $this->payment_settings['sandbox'];

        if ($sandbox) {
            $user = 'conta-business_api1.test.com';
            $pswd = '1365001380';
            $signature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx';
            
            $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $user = $this->payment_settings['user'];
            $pswd = $this->payment_settings['pssword'];
            $signature = $this->payment_settings['signature'];

            $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
        }

        $requestNvp = array(
            'USER' => $user,
            'PWD' => $pswd,
            'SIGNATURE' => $signature,

            'VERSION' => '108.0',
            'METHOD'=> 'SetExpressCheckout',

            'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
            'PAYMENTREQUEST_0_AMT' => $this->payment_settings['valor'],
            'PAYMENTREQUEST_0_CURRENCYCODE' => $this->payment_settings['currency'],
            'PAYMENTREQUEST_0_ITEMAMT' => $this->payment_settings['valor'],
            'PAYMENTREQUEST_0_INVNUM' => $this->payment_settings['codigo'],

            'L_PAYMENTREQUEST_0_NAME0' => $this->payment_settings['produto'],
            'L_PAYMENTREQUEST_0_DESC0' => $this->payment_settings['descricao'],
            'L_PAYMENTREQUEST_0_AMT0' => $this->payment_settings['valor'],
            'L_PAYMENTREQUEST_0_QTY0' => $this->payment_settings['quantidade'],
            'L_PAYMENTREQUEST_0_ITEMAMT' => $this->payment_settings['valor'],

            'RETURNURL' => $this->payment_settings['returnurl'],
            'CANCELURL' => $this->payment_settings['cancelurl']
        );

        $responseNvp = $this->paypal_config($requestNvp, $sandbox);

        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
            $query = array(
                'cmd'    => '_express-checkout',
                'token'  => $responseNvp['TOKEN']
            );

            $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));

            header('Location: ' . $redirectURL);
            
            return true;
        } else {
            return false;
        }
    }
}
?>
