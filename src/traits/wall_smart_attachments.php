<?php
namespace JorryGo\FullVk\traits;

use JorryGo\FullVk\essences\Wall;

trait wall_smart_attachments {
    private function prepareSmartAttachments(array $attachments) : string
    {
        if (empty($attachments)) {
            return '';
        }

        $attachments_result = '';

        foreach ($attachments as $attachment) {
            $delete_file = false;
            $result = false;

            if (filter_var($attachment, FILTER_VALIDATE_URL)) {
                $attachment = $this->downloadFileFromUrl($attachment);
                $delete_file = true;
            }

            if (!file_exists($attachment)) {
                continue;
            }

            $type = mime_content_type($attachment);

            //echo $type . '<br>'; continue;

            //TODO: Working with gifs?
            $type = explode('/', $type)[0];

            switch ($type) {
                case 'image':
                    $result = $this->smartPreparePhoto($attachment);
                    break;
                case 'audio':
                    $result = $this->smartPrepareAudio($attachment);
                    break;
                default:
                    $result = $this->smartPrepareDocument();
            }

            if ($result) {
                $attachments_result .= $result . ',';
            }

            if ($delete_file) {
                unlink($attachment);
            }
        }
        return $attachments_result;
    }

    private function smartPreparePhoto(string $attachment) : string
    {
        $params = [];

        if ($this->essence_type == Wall::TYPE_GROUP) {
            $params['group_id'] = $this->id * -1;
        }

        $upload_server = $this->execute('photos.getWallUploadServer', $params);

        $curl_file = new \CURLFile($_SERVER['DOCUMENT_ROOT'] . '/' . $attachment);
        $params = [];
        $params['photo'] = $curl_file;

        $result = $this->execute('', $params, $upload_server->upload_url, false, true);

        $params = [
            'photo' => $result->photo,
            'server' => $result->server,
            'hash' => $result->hash,
        ];

        if ($this->essence_type == Wall::TYPE_GROUP) {
            $params['group_id'] = $this->id * -1;
        } else {
            $params['user_id'] = $this->id;
        }

        $result = $this->execute('photos.saveWallPhoto', $params);

        $result = array_pop($result);

        return 'photo' . $result->owner_id . '_' . $result->id;
    }

    private function smartPrepareAudio(string $attachment) : string
    {
        $upload_server = $this->execute('audio.getUploadServer');

        $curl_file = new \CURLFile($_SERVER['DOCUMENT_ROOT'] . '/' . $attachment);
        $params = [];
        $params['file'] = $curl_file;

        $result = $this->execute('', $params, $upload_server->upload_url, false, true);

        $params = [
            'server' => $result->server,
            'audio' => $result->audio,
            'hash' => $result->hash,
        ];

        $result = $this->execute('audio.save', $params);

        return 'audio' . $result->owner_id . '_' . $result->id;

    }

    private function smartPrepareDocument(string $attachment) : string
    {
        return '';
    }

    private function downloadFileFromUrl(string $url) : string
    {
        $ext = explode('.', $url);
        $ext = array_pop($ext);

        $file = @file_get_contents($url);

        if (!$file) {
            return '';
        }

        $filename = microtime(true) . rand(1, 99999999) . '.' . $ext;
        file_put_contents($filename, $file);

        return $filename;
    }

}