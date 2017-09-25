<?php
namespace JorryGo\FullVk\traits;

use JorryGo\FullVk\VkException;

trait execute {

    use variables;

    public function execute(string $method, array $params = [], string $query_url = null, bool $fast_responce = true)
    {

        $params['access_token'] = $this->token;
        $params['v'] = $this->api_version;

        $this->ch = curl_init();

        curl_setopt_array($this->ch, array(
            CURLOPT_USERAGENT => 'FullVk/1.0 (+https://github.com/JorryGo/FullVk))',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_URL => ($query_url ?? $this->request_url) . $method
        ));

        $result = json_decode(curl_exec($this->ch));
        curl_close($this->ch);

        if (!empty($result->error)) {
            throw new VkException($result->error . ': ' . $result->error_description);
        }

        return $fast_responce ? $result->response : $result;
    }

}