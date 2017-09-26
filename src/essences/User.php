<?php

namespace JorryGo\FullVk\essences;

use JorryGo\FullVk\traits\execute;

class User {

    use execute;

    public $token;
    public $user_id;

    public function __construct(string $token, int $user_id)
    {
        $this->token = $token;
        $this->user_id = $user_id;
    }

    public function wall($id = 0) : Wall
    {
        return new Wall($this->token, $id);
    }

    public function getProfileInfo()
    {
        return $this->execute('account.getProfileInfo');
    }

    public function getCounters()
    {
        return $this->execute('account.getCounters');
    }

    public function banUser(int $user_id)
    {
        return $this->execute('account.banUser', ['user_id' => $user_id]);
    }

    public function unbanUser(int $user_id)
    {
        return $this->execute('account.unbanbanUser', ['user_id' => $user_id]);
    }

    public function getBanned(int $offset = 0, int $count = 20)
    {
        return $this->execute('account.getBanned', [
            'offset' => $offset,
            'count' => $count,
        ]);
    }
}