<?php
/**
 * Created by PhpStorm.
 * User: fabia
 * Date: 20.10.2017
 * Time: 15:51
 */

namespace HanischIt\GoogleCloudPrint\Model;


class Printer
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    /**
     * Printer constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}