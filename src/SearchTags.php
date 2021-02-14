<?php

namespace WFTags;

use WFTags\{Enum\Types, Exceptions\InvalidSearchTagsExceptions};

class SearchTags
{
    private WarfaceAPI $api;

    public static array $user = [
        'Персонаж неактивен'          => 101,
        'Пользователь не найден'      => 102,
        'Игрок скрыл свою статистику' => 103,
    ];

    public static array $clan = [
        'Клан не найден' => 102
    ];

    private string $type;

    /**
     * SearchTags constructor.
     * @param string $type
     */
    public function __construct(string $type = Types::USER)
    {
        $this->api = new WarfaceAPI($type);
        $this->type = $type;
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
     * @return int
     */
    public function get(string $tag): ?int
    {
        $code = -1;

        for ($server = 1; $server <= 3; $server++)
        {
            try {
                $key = $this->type === Types::USER ? 'name' : 'clan';
                $this->force([$key => $tag, 'server' => $server]);
            }
            catch (InvalidSearchTagsExceptions $e)
            {
                $code = $e->getCode();

                if ($e->getCode() !== 102) {
                    break;
                }
            }
        }

        return $code;
    }

    /**
     * @param string $el
     * @return int
     */
    private function getFailCodeByMessage(string $el): ?int
    {
        return self::${$this->type}[$el] ?? 100;
    }
}