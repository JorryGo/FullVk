<?php
namespace JorryGo\FullVk\essences;

use JorryGo\FullVk\traits\execute;
use JorryGo\FullVk\traits\wall_smart_attachments;
use JorryGo\FullVk\VkException;

class Wall {

    use execute, wall_smart_attachments;

    public $token;
    public $id;
    public $domain;

    public $essence_type;

    const TYPE_USER = 0;
    const TYPE_GROUP = 1;

    public function __construct(string $token, $id)
    {
        $this->token = $token;

        if (is_numeric($id)) {
            $this->id = $id;
            $this->essence_type = $id[0] == '-' ? self::TYPE_GROUP : self::TYPE_USER;
        } else {
            $this->domain = $id;
            $this->findIdByDomain();
        }
    }

    public function getType() : string {
        $types = $this->getTypeList();
        if (empty($types[$this->essence_type])) {
            throw new VkException('Wtf? Wall have undefined type');
        }
        return $types[$this->essence_type];
    }

    public function get(array $params = [])
    {

        $params['owner_id'] = $this->id;
        $params['domain'] = $this->domain;

        return $this->execute('wall.get', $params);
    }

    public function post(array $params) : int
    {
        $params['owner_id'] = $this->id;

        if (!empty($params['smart_attachments'])) {
            if (empty($params['attachments'])) {
                $params['attachments'] = '';
            }

            $params['attachments'] .=
                $params['attachments'][mb_strlen($params['attachments']) - 1] == ','
                    ?
                    $this->prepareSmartAttachments($params['smart_attachments'])
                    :
                    ',' . $this->prepareSmartAttachments($params['smart_attachments']);
        }

        $result = $this->execute('wall.post', $params);
        return $result->post_id;
        return 0;
    }

    private function findIdByDomain()
    {
        if ($this->fundUserIdByDomain()) {
            return true;
        }

        if ($this->findGroupIdByDomain()) {
            return true;
        }

        throw new VkException('No essence found by domain name');
    }

    private function fundUserIdByDomain() : bool
    {
        try {
            $result = $this->execute('users.get', ['user_ids' => $this->domain]);
            $this->id = $result[0]->id;

            $this->essence_type = self::TYPE_USER;

            return true;
        } catch (VkException $e) {
            return false;
        }
    }

    private function findGroupIdByDomain() : bool
    {
        try {
            $result = $this->execute('groups.getById', ['group_ids' => $this->domain]);
            $this->id = '-' . $result[0]->id;

            $this->essence_type = self::TYPE_GROUP;

            return true;
        } catch (VkException $e) {
            return false;
        }
    }

    private function getTypeList() : array
    {
        return [
            self::TYPE_USER => 'user',
            self::TYPE_GROUP => 'group',
        ];
    }

}