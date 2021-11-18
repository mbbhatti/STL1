<?php

namespace App\Util;

use DateTime;
use Exception;
use Closure;

/**
 * Class DBEntity
 * @package App\Utils
 */
class DBEntity
{
    /**
     * Unset attributes you don't need in the API
     *
     * @param $row
     * @param $attributes
     */
    public static function unsetAttributes(&$row, $attributes)
    {
        foreach ($attributes as $attribute) {
            unset($row[$attribute]);
        }
    }

    /**
     * Create a hierarchy from sql results with a parent-child relationship like
     *
     * SELECT parent.id as parent_id, parent.something, child.id, child.some, child.another, child.attribute
     * FROM parent, child
     * WHERE child.parent_id = parent.id
     *
     * @param array $rows the sql results
     * @param int $nbParentAttributes tells how we will slice the rows between parent and children data
     * @param string $childrenAttribute name of the attribute that will contain the list of children
     * @param string $parentAttribute 'parent_id' by convention
     * @return array
     */
    public function toHierarchy(
        Array $rows,
        int $nbParentAttributes,
        string $childrenAttribute = 'children',
        string $parentAttribute = 'parent_id'
    ): array
    {
        $result = [];
        foreach ($rows as $row) {
            $parentId = $row[$parentAttribute];
            if (!key_exists($parentId, $result)) {
                $result[$parentId] = array_slice($row, 0, $nbParentAttributes);
                $result[$parentId][$childrenAttribute] = [];
            }
            $result[$parentId][$childrenAttribute][] = array_slice($row, $nbParentAttributes);
        }

        return array_values($result);
    }

    /**
     * @return Object|null
     */
    public function mysql2ApiMapper(): ?object
    {
        return $this->createMapper([
            'boolean' => ['ok'],
            'integer' => [
                'id', 'version', 'parent_id', 'market', 'items_amount', 'items_sold', 'market_id', 'media_id'
            ],
            'date' => [
                'created_at', 'updated_at', 'date', 'createdAt', 'start_at', 'end_at'
            ],
        ]);
    }

    /**
     * Makes easier the mapping between sql row and what you should return in your api
     *
     * param $fields
     *     keys :   the keys in your mysql table
     *     values:  a CRUDlex type (see http://philiplb.github.io/CRUDlex/docs/html/0.9.10/manual/datatypes.html )
     *
     * Example
     *
     * $closure = createMapper(
     * array(
     * 'date' => array('startAt', 'updated_at'),
     * 'boolean' => array('isDone')
     * 'integer' => array('id', 'chapters')
     * 'float' => array('average')
     * ));
     * $row = $this->db.fetchAssoc($sql1);
     * $normalizedRow = $closure($row);
     *
     * $rows = $this->db.fetchAssoc($sql2);
     * $normalizedRow = $closure($row);
     * @param array $map
     * @return object | null
     */
    public function createMapper(array $map): ?object
    {
        $mapper = function ($row) use ($map) {

            // treat common case of $db->fetchAssoc($sql) returned no data
            if ($row === false) {
                return null;
            }

            $fields = array_key_exists('boolean', $map) ? $map['boolean'] : [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $row)) {
                    $value = $row[$field];
                    $row[$field] = $value ? true : false;
                }
            }

            $fields = array_key_exists('integer', $map) ? $map['integer'] : [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $row)) {
                    $value = $row[$field];
                    $row[$field] = (integer)$value;
                }
            }


            $fields = array_key_exists('date', $map) ? $map['date'] : [];
            $formate = 'Y-m-d\TH:i:s\Z';
            foreach ($fields as $field) {
                if (array_key_exists($field, $row)) {
                    $value = $row[$field];
                    if($field !== 'created_at') {
                        $formate = 'Y-m-d H:i:s';
                    }
                    if($value !== null && is_object($value)) {
                        $row[$field] = $value->format($formate);
                    } else {
                        $row[$field] = date($formate, strtotime($value));
                    }
                }
            }

            $fields = array_key_exists('float', $map) ? $map['float'] : [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $row)) {
                    $value = $row[$field];
                    $row[$field] = (float)$value;
                }
            }

            $fields = array_key_exists('json', $map) ? $map['json'] : [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $row)) {
                    $value = $row[$field];
                    $row[$field] = json_decode($value);
                }
            }

            return $row;
        };

        return $mapper;
    }

    /**
     * @return Closure for when you are only interested in the first column of a sql result
     *
     *   $results = array_map($this->firstColumn(), $rows);
     *
     */
    public function firstColumn(): Closure
    {
        return function ($row) {
            return array_pop($row);
        };
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function standardDbParams(): ?array
    {
        $dateTime = new DateTime();
        $now = $dateTime->format('Y-m-d H:i:s');

        return [
            'version' => 1,
            'createdAt' => new DateTime('@'.strtotime($now)),
            'updatedAt' => new DateTime('@'.strtotime($now)),
            'deletedAt' => null
        ];
    }
}

