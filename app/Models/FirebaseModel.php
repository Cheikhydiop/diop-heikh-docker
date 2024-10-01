<?php

namespace App\Models;

use stdClass;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

abstract class FirebaseModel
{
    protected $database;
    protected $reference;
    protected $path;
    protected $query;
    protected $filters = [];
    protected static $collection;

    

    public function __construct()
    {
        $this->database = $this->getDatabase();
        $this->reference = $this->database->getReference($this->path);
        $this->query = $this->reference;
    }

    protected function getDatabase()
    {
        $serviceAccount = base64_decode("ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZ2VzdGlvbnN0
dWRlbnQtNGY3OTIiLAogICJwcml2YXRlX2tleV9pZCI6ICJjODQ0MDE5NWQ1NGM0ODViZmNmYTFk
NDBhNDZjMTAxN2VlOTljM2ZkIiwKICAicHJpdmF0ZV9rZXkiOiAiLS0tLS1CRUdJTiBQUklWQVRF
IEtFWS0tLS0tXG5NSUlFdmdJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0JLZ3dnZ1NrQWdFQUFv
SUJBUUNtbnQxTjZGU1BVcmpyXG5GVjEyVEJ0YThKeVNId1N5NnJjd0ZQOVJuRWszRmxOYTZsMUxl
RWhNTWVKMFZsRFoyeXduQnVIeGtZem5xOFM2XG5IcUxpUVZBbmtERzVKeWovdlFpRStlMWd6c28x
SFFjODVuaXBNNlR1K1ZFc1ZkS20xaFM5T0RONEhkQm43aW5xXG5aL3h3LzA3aTFRMEtoclV5Rnh3
UnVUNnE2TjJ0ZkJodHQ2TWd4ajNRWkxVZnVUSFd4UDdzcXNES3NPazVkbG9FXG5zNUEzalUzZHRs
VjFnK0pxNmthZ2g2QTBPdTBJSFVtN0lqQ2liMGNjdy8rUmI0Y3lwOG9nSDNBeWFBWDQ0VmJFXG5F
U09uRWtLVDkwSE5rNHl3WVZ5RklVNnJwK3FZNzdtMzBJc0loSldzTzlJbDdzcEx3RkNoM2FnS1lL
d2lZR2MxXG5ocUhwc3Z0ZEFnTUJBQUVDZ2dFQVMzamJTbDVKNnkrdnRrQlR1UEZwVkt4M1oxS29p
TGg3cU1waUNoL3VKQWMzXG5VNCtrR2JyT21NRGgwQXVIRE45VHBRdmZqVkgrRHJSRjVINWJEbnhD
SitHUFBFeHFMRWorVW1sNDVpaFUvSEQ5XG5KYzlKYVc4TXJ2aDhsY1g4S0hrQkpPS1BvY0Z6ZEpn
eklVdmYwM2s1ek5KdldVekNSNEdqUTczeWptenplSkJvXG5QU2xtTnNUbHAzd09DUThHdnEzNVZo
a3FrRHE2OUtUR1hkK0pqbllLdWtWb0Y2WDRjME15aWhPOWgyd2VEK2FKXG5LZlNyVjZZUXArK1hE
WVFrcTlRUGVMWXc1WmdRa0lyODZ3UHRNWTFyK2s0MUpFcnJMYmNyMW5ISXVVbks0TGRYXG5xU1NR
UG0raHRNdnorbUs1OWM4VDhaU2dNSVBLeUo0WlM0SmZSUjl5MndLQmdRRHJIYVBxWGZOTVVyZmor
VWNDXG5MeVZab0t6UVdzdXhrWC9FZTM3LzA1NHhXd3FDMk9jVS9MN0xzQzFLM01ScGY4MkxRbnpR
Z0dTVHI0cVZoOE0xXG5yeGJHaWFCOHZPQmNuSGE4RTNDb3UvRXBYQ01NWFBaU3RCVGIzUnpYdDNE
TVlYczdROWNxQ0tVSlRNQVVGdGVjXG5SUWJTTGZQd1VRdDRsMDBtQWhJUTBGL0tDd0tCZ1FDMWE3
R0FzbGZUd2QyUUFFeGoxdTFnS09KNjFnaE1GQVZmXG5yZTlGM0JaMXVZRUtvSFZKb3RSVnNlU2RO
ZUtaZlFTVEhIYkhaU3QyMHNWZmp4eGd3czFtTDE4d1dEU0tVSXFFXG5TUk1KUFpOWFdyNnZNYlFX
bWVjODQ5Vy9tR3lSQ0hNUVFCbTB1alkxQWFvU01xWVZ5TjI5M2JlT0xHOGIyYTU5XG55bHM4aU5x
Wk53S0JnUUMrZlpOU3phL1R4S2tzMGNqUVlqWld1YkdLVmlYMnhqWElrVzVDQThpRm9JZVJuLzhK
XG5xQVZGcm9Yanc1NTNBNDhiSjFGTTlObUlObytIdXlsVUUzbldmYWREcVJ2MnJPWWVWM25ObkFn
MlJHbzVHQkNTXG56VWloSWpLK0srZitEM1R5ZlhLVW9kQmxDb1FOUXJ5SjVmc3l5dng3Vm81K3M4
ZFQ1aFdRL2VLK0pRS0JnRlRBXG5jclBFblBMMzVpR1hnaGhDRjdLTVp3RXJhOFRldFlQMUpZcGxQ
cmpRYUZBbWxhUkl4UmtqZWNGWG92eEJReG5XXG5la2E4SklubC9QNmZSSXFQZHBUK0hHSGhVdW9x
cGdzV2VDWWc5ZXdoSUdPSHZMR3lSQ3hWNDAwV1QrR1JLTysyXG5na3ZEa2Y5QlA4b3dqeFl1T0Np
eW8wVnZ6SmdlSVQzeWdwek04aElSQW9HQkFObEh2Q050RGs4NVI5SDhIcW9jXG5GU1F4MlcxRkNZ
NmJDRlZ0UTRwTTRkYU8vVHorMWhhMXptaDlucXZQUHhnbnhLTTRzbGZ4OWQwakJOQTlFSC9HXG52
c0NualNBUDFqNWYwN1A1Q2kxMXZEa1NIWjVQZDBSakU3aERaR0pGcUFubUxGRVBPQWlOSEtGT3d2
aVRWTHlQXG5wU0VBUkwzV1V2NHVXbzRoVng0bUJIQ2pcbi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0t
LS1cbiIsCiAgImNsaWVudF9lbWFpbCI6ICJmaXJlYmFzZS1hZG1pbnNkay03MTg2YUBnZXN0aW9u
c3R1ZGVudC00Zjc5Mi5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgImNsaWVudF9pZCI6ICIx
MTcxOTgwMzUxNjYyNzQ3ODU5OTIiLAogICJhdXRoX3VyaSI6ICJodHRwczovL2FjY291bnRzLmdv
b2dsZS5jb20vby9vYXV0aDIvYXV0aCIsCiAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5n
b29nbGVhcGlzLmNvbS90b2tlbiIsCiAgImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6ICJo
dHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9vYXV0aDIvdjEvY2VydHMiLAogICJjbGllbnRfeDUw
OV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9yb2JvdC92MS9tZXRhZGF0
YS94NTA5L2ZpcmViYXNlLWFkbWluc2RrLTcxODZhJTQwZ2VzdGlvbnN0dWRlbnQtNGY3OTIuaWFt
LmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5j
b20iCn0=");
        $decodedAccount = json_decode($serviceAccount, true);


        $factory = (new Factory())
            ->withDatabaseUri('https://gestionstudent-4f792-default-rtdb.firebaseio.com')
            ->withServiceAccount($decodedAccount);
        return $factory->createDatabase();
    }

    public function getReference($collection)
    {
        return $this->database->getReference($collection);
    }

    public function all()
    {
        $result = $this->reference->getValue();
        return $result === null ? [] : $result;
    }

    public function find($id)
    {
        $result = $this->reference->getChild($id)->getValue();
        return $result === null ? new stdClass() : (object) $result;
    }

    public function create(array $data)
    {
        $reference = $this->reference;
        $existingUsers = $reference->orderByKey()->getValue();
        $nextId = 1;
        if ($existingUsers) {
            $keys = array_keys($existingUsers);
            $keys = array_map('intval', $keys);
            $nextId = max($keys) + 1;
        }
        $newRef = $reference->getChild((string) $nextId);
        $newRef->set($data);
        return $nextId;
    }

    public function update($id, array $data)
    {
        $this->reference->getChild($id)->update($data);
        return $id;
    }

    public function delete($id)
    {
        $this->reference->getChild($id)->remove();
        return $id;
    }

    public static function query()
    {
        return new static;
    }

    public function where($field, $operator, $value)
    {
        $this->filters[] = [$field, $operator, $value];
        return $this;
    }

    public function paginate($perPage = 15, $page = 1)
    {
        $all = $this->all();
        $total = count($all);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($all, $offset, $perPage);

        return [
            'current_page' => $page,
            'data' => $items,
            'from' => $offset + 1,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'to' => min($offset + $perPage, $total),
            'total' => $total,
        ];
    }

    public function get()
    {
        $result = $this->reference->getValue();
        
        if (!empty($this->filters)) {
            $result = $this->applyFilters($result);
        }
        
        $this->filters = []; // Reset filters
        return $result === null ? [] : $result;
    }

    public function first()
    {
        $result = $this->get();
        return !empty($result) ? reset($result) : null;
    }

    public function count()
    {
        return count($this->all());
    }

    public function orderBy($field, $direction = 'asc')
    {
        $query = $this->reference->orderByChild($field);
        $result = $query->getValue();
        
        if ($direction === 'desc') {
            $result = array_reverse($result);
        }
        
        return $result;
    }

    public function limit($count)
    {
        return $this->reference->limitToFirst($count)->getValue();
    }

    public function pluck($field)
    {
        $result = $this->all();
        return array_column($result, $field);
    }

    public function findMany(array $ids)
    {
        $results = [];
        foreach ($ids as $id) {
            $result = $this->find($id);
            if ($result) {
                $results[$id] = $result;
            }
        }
        return $results;
    }

    public function whereIn($field, array $values)
    {
        $results = [];
        foreach ($values as $value) {
            $query = $this->reference->orderByChild($field)->equalTo($value);
            $result = $query->getValue();
            if ($result) {
                $results = array_merge($results, $result);
            }
        }
        return $results;
    }

    public function whereBetween($field, $start, $end)
    {
        return $this->reference->orderByChild($field)
            ->startAt($start)
            ->endAt($end)
            ->getValue();
    }

    public function increment($id, $field, $amount = 1)
    {
        $currentValue = $this->find($id)[$field] ?? 0;
        return $this->update($id, [$field => $currentValue + $amount]);
    }

    public function decrement($id, $field, $amount = 1)
    {
        return $this->increment($id, $field, -$amount);
    }

    public function push(array $data)
    {
        $newRef = $this->reference->push();
        $newRef->set($data);
        return $newRef->getKey();
    }

    public function chunk($count, callable $callback)
    {
        $all = $this->all();
        $chunks = array_chunk($all, $count, true);
        foreach ($chunks as $chunk) {
            if ($callback($chunk) === false) {
                break;
            }
        }
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    protected function applyFilters($data)
    {
        return array_filter($data, function($item) {
            if (!is_array($item)) {
                return false;
            }
            foreach ($this->filters as $filter) {
                [$field, $operator, $value] = $filter;
                if (!isset($item[$field])) {
                    return false;
                }
                if (!$this->evaluateCondition($item[$field], $operator, $value)) {
                    return false;
                }
            }
            return true;
        });
    }

    protected function evaluateCondition($fieldValue, $operator, $value)
    {
        switch ($operator) {
            case '=':
                return $fieldValue == $value;
            case '>':
                return $fieldValue > $value;
            case '<':
                return $fieldValue < $value;
            case '>=':
                return $fieldValue >= $value;
            case '<=':
                return $fieldValue <= $value;
            case '!=':
                return $fieldValue != $value;
            default:
                return false;
        }
    }
}