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

    public function getProfileInfo()
    {
        return $this->execute('account.getProfileInfo');
    }

    public function getCounters() {
        return $this->execute('account.getCounters');
    }

    public function banUser($user_id) {
        return $this->execute('account.banUser', ['user_id' => $user_id]);
    }
}