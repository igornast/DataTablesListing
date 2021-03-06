<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 2015-01-16
 * Time: 20:02
 */

namespace PawelLen\DataTablesListing\Column\Type;

use Symfony\Component\PropertyAccess\PropertyAccess;


class ListingColumn extends ListingColumnType
{
    /**
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);
    }


    /**
     * @return bool
     */
    public function isSortable()
    {
        if (isset($this->options['order_by']) && !$this->options['order_by']) {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function getValues($row)
    {
        $property = isset($this->options['property']) ? $this->options['property'] : $this->getName();
        $value = $this->getPropertyValue($row, $property);

        // Process value using callback:
        if (isset($this->options['callback']) && is_callable($this->options['callback'])) {
            $value = $this->options['callback']($value, $row, $this);
        }

        // Build parameters:
        $parameters = array();
        if (isset($this->options['parameters'])) {
            foreach ($this->options['parameters'] as $name => $propertyPath) {
                $parameters[$name] = $this->getPropertyValue($row, $propertyPath);
            }
        }

        return array(
            'value' => $value,
            'parameters' => $parameters,
            'options' => $this->options,
            'name' => $this->name,
            'row' => $row
        );
    }


    /**
     * @return string
     */
    public function getType()
    {
        return 'column';
    }

}