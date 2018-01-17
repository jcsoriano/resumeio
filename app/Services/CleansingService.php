<?php

namespace App\Services;

class CleansingService
{
    public function cleanse($resume)
    {
        return $this->cleanseObject($resume, $this->fillableStructure);
    }

    public function cleanseProperty($value, $structure)
    {
        if ($structure['type'] == 'string') {
            if (is_string($value)) {
                return $value;
            }
        } if ($structure['type'] == 'stringOrArray') {
            if (is_string($value) || is_array($value)) {
                return $value;
            }
        } elseif ($structure['type'] == 'number') {
            if (is_numeric($value)) {
                return $value;
            }
        } elseif ($structure['type'] == 'boolean') {
            return $this->cleanseBoolean($value);
        } elseif ($structure['type'] == 'array') {
            return $this->cleanseArray($value, $structure['structure']);
        } elseif ($structure['type'] == 'object') {
            return $this->cleanseObject($value, $structure['structure']);
        } elseif ($structure['type'] == 'function') {
            $function = $structure['function'];
            return $this->$function($value);
        }
        return null;
    }

    public function cleanseArray($array, $structure)
    {
        if (is_array($array)) {
            $newArray = [];
            foreach ($array as $item) {
                $newArray[] = $this->cleanseObject($item, $structure);
            }
            return $newArray;
        }
        return null;
    }

    public function cleanseObject($object, $structure)
    {
        if (is_array($object)) {
            $object = array_only($object, array_keys($structure));
            $cleansedObject = [];
            foreach ($object as $property => $value) {
                $cleansedObject[$property] = $this->cleanseProperty($value, $structure[$property]);
            }
            return $cleansedObject;
        }
        return null;
    }

    public function cleanseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }
        
        if (in_array($value, array('true', '1', 'yes', 1), true)) {
            return true;
        }

        if (in_array($value, array('false', '0', 'no', 0), true)) {
            return false;
        }

        return null;
    }
}
