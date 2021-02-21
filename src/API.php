<?php

namespace WFTags;

use WFTags\{Enums\Types, Exceptions\InvalidSearchTagsExceptions};

class API
{
    private const HOST_API = 'http://api.warface.ru/';

    private array $branches = [
        Types::USER => 'user/stat',
        Types::CLAN => 'clan/members'
    ];

    private string $head;

    /**
     * Request constructor.
     * @param string $el
     */
    public function __construct(string $el)
    {
        $this->head = $this->branches[$el];
    }

    /**
     * @param array $params
     */
    public function get(array $params)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::HOST_API . $this->head . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $el = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($el, true);

        throw new InvalidSearchTagsExceptions($result['message'] ?? '');
    }
}