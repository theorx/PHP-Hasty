<?php

class XmlGenerator {

    /**
     * Generates xml from object / array
     * @author Lauri Orgla
     * @version 1.0
     * @param type $object
     * @return boolean
     */
    public function xml($object) {
        if (is_object($object) || is_array($object)) {
            $xml = new SimpleXMLElement(sprintf("<%s></%s>", Config::get('xml_response_root_node'), Config::get('xml_response_root_node')));
            $this->buildXml($object, $xml);
            $dom = new DOMDocument();
            $dom->loadXML($xml->asXML());
            $dom->formatOutput = true;
            return $dom->saveXML();
        }
        return false;
    }

    /**
     * Generates object from xml
     * @author Lauri Orgla
     * @version 1.0
     * @param type $xml
     * @return boolean
     */
    public function obj($xml) {
        if (strlen($xml) > 0) {
            $xml_obj = simplexml_load_string($xml);
            return json_decode(json_encode($xml_obj), false);
        }
        return false;
    }

    /**
     * Recursive function for iterating through object's children
     * @author Lauri Orgla
     * @version 1.0
     * @param type $object
     * @param type $xml
     */
    private function buildXml($object, $xml) {
        foreach ($object as $key => $value) {
            if (is_object($value) || is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    $this->buildXml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item");
                    $this->buildXml($value, $subnode);
                }
            } else {
                if (!is_numeric($key)) {
                    $xml->addChild("$key", $value);
                } else {
                    $xml->addChild("item", $value);
                }
            }
        }
    }

}

?>