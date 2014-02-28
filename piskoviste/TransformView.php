<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransformView
 *
 * @author pes2704
 */

/**
 * A classic entity class, part of the Domain Model.
 */
class User
{
    private $_name;
    private $_city = '';

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setCity($city)
    {
        $this->_city = $city;
    }

    public function getCity()
    {
        return $this->_city;
    }
}

class TransformView
{
    /**
     * Iterates through getters and calls them to display $entity.
     * @param object $entity
     * @return string
     */
    public function display($entity)
    {
        $result = "<table>\n";
        $rc = new ReflectionClass(get_class($entity));
        foreach ($rc->getMethods() as $method) {
            $methodName = $method->getName();
            if (strstr($methodName, 'get') == $methodName) {
                $field = str_replace('get', '', $methodName);
                $result .= "<tr>\n";
                $result .= "<td>{$field}</td>\n";
                $result .= "<td>" . $entity->$methodName() . "</td>\n";
                $result .= "</tr>\n";
            }
        }
        $result .= "</table>\n";
        return $result;
    }
}

$user = new User('Giorgio');
$user->setCity('Como');
$view = new TransformView();
echo $view->display($user);