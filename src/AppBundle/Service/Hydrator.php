<?php

namespace AppBundle\Service;

use Doctrine\Common\Inflector\Inflector;

class Hydrator
{
    /**
     * @param array  $data
     * @param string $type
     *
     * @return bool|object
     */
    public function hydrate(array $data, $type)
    {
        $class_names = $this->classNames($type);

        if (class_exists($class_names['namespaced'])) {
            $entity = new $class_names['namespaced']();

            foreach ($data as $k => $v) {
                $property = $this->whichProperty($entity, $k);

                if ($property) {
                    $proper_property_name = ucfirst($property);

                    $set_method = 'set' . $proper_property_name;
                    $add_method = 'add' . Inflector::singularize($proper_property_name);

                    if (method_exists($entity, $set_method)) {
                        $entity->$set_method($v);
                    } elseif (method_exists($entity, $add_method)) {
                        $reflection = new \ReflectionMethod($entity, $add_method);
                        $params     = $reflection->getParameters();

                        $class = $params[0]->getClass();

                        if (is_array($v)) {
                            foreach ($v as $entry) {
                                $subclass = $this->hydrate($entry, $class->getName());

                                $entity->$add_method($subclass);
                            }
                        } else {
                            $subclass = $this->hydrate($v, $class->getName());

                            if (property_exists($subclass, 'parent')) {
                                $subclass->setParent($data['id']);
                            }

                            $entity->$add_method($subclass);
                        }
                    }
                }
            }

            return $entity;
        }

        return FALSE;
    }

    /**
     * @param string $class
     *
     * @return array
     */
    private function classNames($class)
    {
        if (strpos($class, 'AppBundle\\Entity') === FALSE) {
            $proper      = ucwords($class);
            $class_names = [
                'name'       => $class,
                'namespaced' => 'AppBundle\\Entity\\' . $proper,
                'proper'     => $proper
            ];
        } else {
            $explode = explode('\\', $class);

            $class_names = [
                'name'       => strtolower($explode[2]),
                'namespaced' => $class,
                'proper'     => $explode[2]
            ];
        }

        return $class_names;
    }

    /**
     * @param object $entity
     * @param string $property
     *
     * @return bool|string
     */
    private function whichProperty($entity, $property)
    {
        $plural = Inflector::pluralize($property);

        if (property_exists($entity, $property)) {
            return $property;
        } elseif (property_exists($entity, $plural)) {
            return $plural;
        }

        return FALSE;
    }
}