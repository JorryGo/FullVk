<?php
namespace JorryGo\FullVk\traits;

use JorryGo\FullVk\VkException;

trait execute {

    use variables;

    public static $execute_per_second = 2;
    public static $last_execute_time;

    public function execute(string $method, array $params = [], string $query_url = null, bool $fast_responce = true, bool $file = false)
    {
        if (!$file) {
            $params['access_token'] = $this->token;
            $params['v'] = $this->api_version;
        }

        $this->ch = curl_init();

        curl_setopt_array($this->ch, [
            CURLOPT_USERAGENT => 'FullVk/1.0 (+https://github.com/JorryGo/FullVk))',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $file ? $params : http_build_query($params),
            CURLOPT_URL => ($query_url ?? $this->request_url) . $method
        ]);

        $result = json_decode(curl_exec($this->ch));
        curl_close($this->ch);

        if (self::$last_execute_time) {
            $check = microtime(true) - self::$last_execute_time;
            if (1/self::$execute_per_second < $check) {
                usleep(1 - $check * 1000000);
            }
        }

        self::$last_execute_time = microtime(true);

        if (!empty($result->error)) {
            if (is_string($result->error)) {
                throw new VkException($result->error . ': ' . $result->error_description);
            }
            throw new VkException('Code ' . $result->error->error_code . ': ' . $result->error->error_msg);
        }

        return $fast_responce ? $result->response : $result;
    }

}