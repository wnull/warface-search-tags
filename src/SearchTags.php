<?php

namespace WFTags;

use WFTags\Exceptions\InvalidSearchTagsExceptions;
use WFTags\Enums\{Messages\Clan, Messages\User, Status, Types};

class SearchTags
{
    private API $api;

    private array $user = [
        User::INACTIVE_USER  => Status::INACTIVE,
        User::USER_NOT_FOUND => Status::NOT_FOUND,
        User::HIDDEN_USER    => Status::HIDDEN,
    ];

    private array $clan = [
        Clan::CLAN_NOT_FOUND => Status::NOT_FOUND
    ];

    private array $data, $params;

    /**
     * SearchTags constructor.
     * @param string $type
     */
    public function __construct(string $type = Types::USER)
    {
        $this->api = new API($type);
        $this->data = $this->{$type};

        switch ($type) {
            case Types::USER:
                $this->params = ['key' => 'name', 'msg' => User::EXIST_USER];
                break;

            case Types::CLAN:
                $this->params = ['key' => 'clan', 'msg' => Clan::EXIST_CLAN];
                break;
        }
    }

    /**
     * @param array $params
     */
    private function force(array $params): void
    {
        try {
            $this->api->get($params);
        }
        catch (InvalidSearchTagsExceptions $e) {
            throw new InvalidSearchTagsExceptions($e->getMessage(), $this->getFailCodeByMessage($e->getMessage()));
        }
    }

    /**
     * @param string $tag
     * @return array
     */
    public function get(string $tag): array
    {
        $code = -1;
        $message = '';

        for ($server = 1; $server <= 3; $server++)
        {
            try {
                $this->force([$this->params['key'] => $tag, 'server' => $server]);
            }
            catch (InvalidSearchTagsExceptions $e)
            {
                $code = $e->getCode();
                $message = $e->getMessage();

                if ($code !== Status::NOT_FOUND) {
                    $message = $this->params['msg'];
                    break;
                }
            }
        }

        return [
            'tag'  => $tag,
            'code' => $code,
            'msg'  => $message
        ];
    }

    /**
     * @param string $el
     * @return int
     */
    private function getFailCodeByMessage(string $el): int
    {
        return $this->data[$el] ?? Status::EXISTS;
    }
}