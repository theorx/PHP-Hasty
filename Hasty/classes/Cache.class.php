<?php

class Cache {

    /**
     * Reads data from filesystem. location is cache_path from config
     * @author Lauri Orgla
     * @param string $file
     * @return boolean
     */
    private static function read($file) {
        $file = Config::get('cache_path') . $file;
        if (file_exists($file))
            return file_get_contents($file);
        return false;
    }

    /**
     * Writes data to filesystem. location is cache_path from config
     * @author Lauri Orgla
     * @param string $file
     * @param string $data
     * @return boolean
     */
    private static function write($file, $data) {
        $file = Config::get('cache_path') . $file;
        file_put_contents($file, $data);
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * deletes cache file
     * @param string $file
     * @return boolean
     */
    private static function delete($file) {
        $file = Config::get('cache_path') . $file;
        unlink($file);
        return true;
    }

    /**
     * Retrieves value from cache if cache expired then false
     * @author Lauri Orgla
     * @param string $offset
     * @return boolean
     */
    public static function getValue($offset) {
        $name = md5($offset . '_value') . md5($offset . strrev($offset));
        if (self::read($name . '_value_ttl') < time()) {
            return false;
        }
        $value = self::read($name);
        return ($value == false) ? false : $value;
    }

    /**
     * Retrieves object from cache if cache expired then false
     * @author Lauri Orgla
     * @param type $offset
     * @return boolean
     */
    public static function getObject($offset) {
        $name = md5($offset . '_object') . md5($offset . strrev($offset));
        if (self::read($name . '_object_ttl') < time()) {
            return false;
        }
        $value = self::read($name);
        return ($value == false) ? false : unserialize($value);
    }

    /**
     * This will store value in cache
     * ttl is in unix time
     * @author Lauri Orgla
     * @param string $offset
     * @param string $value
     * @param int $ttl
     * @return boolean
     */
    public static function storeValue($offset, $value, $ttl) {
        $name = md5($offset . '_value') . md5($offset . strrev($offset));
        self::write($name, $value);
        self::write($name . '_value_ttl', (time() + $ttl));
        return true;
    }

    /**
     * This will store object in cache
     * ttl is in unix time
     * @author Lauri Orgla
     * @param string $offset
     * @param object|mixed $object
     * @param int $ttl
     * @return boolean
     */
    public static function storeObject($offset, $object, $ttl) {
        $name = md5($offset . '_object') . md5($offset . strrev($offset));
        self::write($name, serialize($object));
        self::write($name . '_object_ttl', (time() + $ttl));
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $offset
     * @return boolean
     */
    public static function deleteValue($offset) {
        $file = md5($offset . '_value') . md5($offset . strrev($offset));
        $ttl = md5($offset . '_value_ttl') . md5($offset . strrev($offset));
        self::delete($file);
        self::delete($ttl);
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $offset
     * @return boolean
     */
    public static function deleteObject($offset) {
        $file = md5($offset . '_object') . md5($offset . strrev($offset));
        $ttl = md5($offset . '_object_ttl') . md5($offset . strrev($offset));
        self::delete($file);
        self::delete($ttl);
        return true;
    }

    /**
     * Checks if value cache is valid. If cache has timed out returns false.
     * This prevents reading cache when its not required
     * @author Lauri Orgla
     * @param string $offset
     * @return boolean
     */
    public static function checkValueCache($offset) {
        $name = md5($offset . '_value') . md5($offset . strrev($offset));
        if (self::read($name . '_value_ttl') < time()) {
            return false;
        }
        return true;
    }

    /**
     * Checks if object cache is valid. If cache has timed out returns false.
     * This prevents reading cache when its not required
     * @author Lauri Orgla
     * @param string $offset
     * @return boolean
     */
    public static function checkObjectCache($offset) {
        $name = md5($offset . '_object') . md5($offset . strrev($offset));
        if (self::read($name . '_object_ttl') < time()) {
            return false;
        }
        return true;
    }

}

?>
