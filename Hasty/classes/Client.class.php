<?php

class Client {

    /**
     * holds user id from database
     * @author Lauri Orgla
     * @version 1.0
     * @var integer 
     */
    public static $id = 0;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_permission_groups;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_user_data;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var type 
     */
    private static $_group_permission_data;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_parsed_groups;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_user_permission_data;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_compiled_permissions;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    private static $_keywords;

    /**
     * @author Lauri Orgla
     * @version 1.0
     */
    private static function compilePermissions() {
        if (!self::$_user_data) {
            self::$_user_data = Sql::fetch('SELECT * FROM users WHERE id = :user_id', array(':user_id' => self::$id));
        }

        if (!self::$_permission_groups) {
            self::$_permission_groups = Sql::fetchAll('SELECT * FROM permission_groups');
        }

        if (!self::$_group_permission_data && isset(self::$_user_data->user_group)) {
            self::cycleGroupsRecursive(self::$_permission_groups, self::$_user_data->user_group);
            self::$_group_permission_data = Sql::fetchAll('SELECT * FROM group_permissions WHERE group_id IN(' . implode(',', self::$_parsed_groups) . ')');
            self::parseGroupsPermissions(self::$_group_permission_data);
        }

        if (!self::$_user_permission_data && isset(self::$_user_data->id)) {
            self::$_user_permission_data = Sql::fetchAll('SELECT * FROM user_permissions WHERE user_id = :user_id', array(':user_id' => self::$_user_data->id));
            self::parseUserPermissions(self::$_user_permission_data);
        }
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $groups_data
     * @param mixed $group
     * @return null
     */
    private static function cycleGroupsRecursive($groups_data, $group) {
        if (!is_array($groups_data)) {
            return;
        }
        self::$_parsed_groups[] = $group;
        foreach ($groups_data as $node) {
            if ($node->id == $group) {
                self::$_group_permission_data[] = $node;
                if (!in_array($node->parent, self::$_parsed_groups)) {
                    self::cycleGroupsRecursive($groups_data, $node->parent);
                }
            }
        }
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $permissions_data
     */
    private static function parseGroupsPermissions($permissions_data) {
        foreach (array_reverse(self::$_parsed_groups) as $group_id) {
            foreach ($permissions_data as $node) {
                if ($node->group_id == $group_id) {
                    if ($node->type == "CLASS") {
                        $pieces = explode(":", $node->data);
                        if (count($pieces) == 2) {
                            self::$_compiled_permissions[strtolower($pieces[0])][$pieces[1]] = array(
                                "create" => $node->create,
                                "read" => $node->read,
                                "update" => $node->update,
                                "delete" => $node->delete
                            );
                        }
                    } else {
                        self::$_keywords[$node->data] = $node->read;
                    }
                }
            }
        }
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $permissions_data
     */
    private static function parseUserPermissions($permissions_data) {
        foreach ($permissions_data as $node) {
            if ($node->type == "CLASS") {
                $pieces = explode(":", $node->data);
                if (count($pieces) == 2) {
                    self::$_compiled_permissions[strtolower($pieces[0])][$pieces[1]] = array(
                        "create" => $node->create,
                        "read" => $node->read,
                        "update" => $node->update,
                        "delete" => $node->delete
                    );
                }
            } else {
                self::$_keywords[$node->data] = $node->read;
            }
        }
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $keyword
     * @return boolean
     */
    public static function hasRight($keyword) {
        self::compilePermissions();

        if (isset(self::$_keywords[$keyword]) && self::$_keywords[$keyword] == 1) {
            return true;
        }

        return false;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param string $class
     * @param string $function
     * @param string $api_method
     * @return boolean
     */
    public static function hasAccess($class, $function = false, $api_method = "read") {
        self::compilePermissions();

        if (in_array($class, Config::get('public_controllers'))) {
            return true;
        }

        if (!in_array($api_method, ['read', 'create', 'delete', 'update'])) {
            $api_method = "read";
        }
        if (isset(self::$_compiled_permissions[strtolower($class)]['*']) &&
                !isset(self::$_compiled_permissions[strtolower($class)][$function]) &&
                self::$_compiled_permissions[strtolower($class)]['*'][$api_method] == 1) {

            return true;
        } else if (isset(self::$_compiled_permissions[strtolower($class)][$function]) && self::$_compiled_permissions[strtolower($class)][$function][$api_method] == true) {
            return true;
        }
        return false;
    }

}

?>