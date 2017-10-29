<?php
namespace Tzsk\ScrapePod\Helpers;

use Exception;
use tidy;

class Xml
{
    /**
     * @param string $xml
     *
     * @return string
     * @throws Exception
     */
    public static function repair($xml)
    {
        if (class_exists("Tidy") === false) {
            throw new Exception("Tidy Class not found", 500);
        }

        $config = [
            'indent'     => true,
            'input-xml'  => true,
            'output-xml' => true,
            'wrap'       => false
        ];

        $xml_repaired = new Tidy();
        $xml_repaired->parseString($xml, $config, 'utf8');
        $xml_repaired->cleanRepair();

        return $xml_repaired;
    }
}
