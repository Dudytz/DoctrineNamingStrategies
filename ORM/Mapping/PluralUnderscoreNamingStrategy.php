<?php

namespace EduardoLeggiero\DoctrineNamingStrategiesBundle\ORM\Mapping;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\Common\Inflector\Inflector;

/**
 * Naming strategy implementing the underscore naming convention and pluralize.
 * Converts 'MyEntity' to 'my_entities' or 'MY_ENTITIES'.
 *
 *
 * @link    http://github.com/EduardoLeggiero/DoctrineNamingStrategiesBundle
 * @since   2.3
 * @author  Eduardo Leggiero
 */
class PluralUnderscoreNamingStrategy extends UnderscoreNamingStrategy
{
    /**
     * @var integer
     */
    private $case;

    /**
     * Plural underscore naming strategy construct
     *
     * @param integer $case CASE_LOWER | CASE_UPPER
     */
    public function __construct($case = CASE_LOWER)
    {
        parent::__construct($case);
        $this->case = $case;
    }

    /**
     * {@inheritdoc}
     */
    public function classToTableName($className)
    {
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }

        return $this->underscorePlural($className);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function underscorePlural($string)
    {
        $string = preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $string);

        $string = preg_replace('/([^_]+)$/e', '$this->plural(\'$1\')', $string);

        if ($this->case === CASE_UPPER) {
            return strtoupper($string);
        }

        return strtolower($string);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function plural($string)
    {
        if (in_array(strtolower($string), array(
            'status'
            ))) {
            return $string;
        }

        return Inflector::pluralize($string);
    }
}
