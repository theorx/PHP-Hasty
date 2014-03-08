<?php

class Documentor {

    public function fetchFunctions($source) {
        $data = token_get_all($source);

        foreach ($data as $key => &$val) {
            $val[0] = token_name((int) $val[0]);

            if (!in_array($val[0], array('T_PUBLIC', 'T_FUNCTION', 'T_STRING', 'T_DOC_COMMENT', 'T_PRIVATE')) || !is_array($val)) {
                unset($data[$key]);
            }
        }

        $data = array_values($data);
        $bundles = array();

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i][0] == 'T_FUNCTION') {
                $bundle = array("type" => '', "comment" => '', "function" => '');
                if (isset($data[$i - 1]) && in_array($data[$i - 1][0], array('T_PUBLIC', 'T_PRIVATE', 'T_FINAL', 'T_PROTECTED'))) {
                    $bundle['type'] = str_replace("T_", "", $data[$i - 1][0]);
                    if (isset($data[$i - 2]) && in_array($data[$i - 2][0], array('T_DOC_COMMENT'))) {
                        $bundle['comment'] = $data[$i - 2][1];
                    }
                } else if (isset($data[$i - 1]) && in_array($data[$i - 1][0], array('T_DOC_COMMENT'))) {
                    $bundle['type'] = 'PUBLIC';
                    $bundle['comment'] = $data[$i - 1][1];
                } else if (isset($data[$i - 2]) && in_array($data[$i - 2][0], array('T_DOC_COMMENT'))) {
                    $bundle['type'] = 'PUBLIC';
                    $bundle['comment'] = $data[$i - 2][1];
                } else if (isset($data[$i - 1]) && !in_array($data[$i - 1][0], array('T_PUBLIC', 'T_PRIVATE', 'T_DOC_COMMENT', 'T_FINAL', 'T_PROTECTED'))) {
                    $bundle['type'] = 'PUBLIC';
                }
                if (isset($data[$i + 1]) && $data[$i + 1][0] == 'T_STRING') {
                    $bundle['function'] = $data[$i + 1][1];
                }
                //parse the comment before returning
                $bundle['comment'] = $this->parseComment($bundle['comment']);
                $bundles[] = $bundle;
            }
        }
        return $bundles;
    }

    public function parseComment($input) {
        $input = preg_replace('/[ \t]+/', ' ', $input);
        $input = explode(PHP_EOL, str_replace(['/', '*'], '', $input));
        $result = array();
        foreach ($input as $key => $value) {
            $pieces = explode(" ", ltrim($value));

            if (isset($pieces[0]) && substr($pieces[0], 0, 1) == '@') {
                $result[str_replace('@', '', $pieces[0])] = implode(" ", array_slice($pieces, 1));
            } else if (isset($pieces[0]) && strlen($value) > 0) {
                if (isset($result["description"])) {
                    $result["description"] .= $value . PHP_EOL;
                } else {
                    $result["description"] = $value . PHP_EOL;
                }
            }
        }


        return $result;
    }

}

?>