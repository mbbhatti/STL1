<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class EtagEntity
 * @package App\Utils
 */
class EtagEntity extends DBEntity
{
    /**
     * @return int
     */
    public function todayEtag(): int
    {
        $DATE = 'Ymd';
        $str = date($DATE, time());

        return (int)$str;
    }

    /**
     * @param $data
     * @return array
     */
    public function headersWithEtag($data): array
    {
        $timestamp = 0;
        foreach ($data as $row) {
            $etag = $row['etag'];
            if ($etag !== null) {
                $timestamp = max($timestamp, strtotime($etag));
            }
        }

        return ['ETag' => $timestamp];
    }

    /**
     * @param Request $request
     * @param array $responseHeaders
     * @return bool
     */
    public function notModifiedSince(Request $request, Array $responseHeaders): bool
    {
        if (!key_exists('ETag', $responseHeaders)) {
            return false;
        }

        $timestamp = $responseHeaders['ETag'];
        $timestamp .= ''; // make sure string is not null
        $sentEtag = $request->headers->get('if-none-match', '');

        return $sentEtag == $timestamp;
    }
}

