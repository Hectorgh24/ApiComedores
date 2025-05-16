<?php

class XmlHandler {
    public static function generarXml($data, $rootElement, $childElement = null) {
        $xml = new SimpleXMLElement("<$rootElement/>");
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            foreach ($data as $item) {
                $child = $xml->addChild($childElement);
                foreach ($item as $key => $value) {
                    // Verificar si el valor es null y convertirlo a string vacío
                    $value = $value === null ? '' : $value;
                    $child->addChild($key, htmlspecialchars($value));
                }
            }
        } else {
           foreach ($data as $key => $value) {
                // Verificar si el valor es null y convertirlo a string vacío
                $value = $value === null ? '' : $value;
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }
}
?>

