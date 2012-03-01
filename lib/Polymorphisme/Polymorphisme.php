<?php
namespace Attraktiv\Polymorphisme;

class Polymorphisme
{

    public static function transtypage($expected, $actual)
    {
        $poly = new self;
        $expectedListOfClass = $poly->getImplementationList($expected);
        $actualListOfClass = $poly->getImplementationList($actual);
        $commonsImplementationList = $poly->findCommonImplementation($expectedListOfClass, $actualListOfClass);

        $poly->throwExceptionIfNotCommonsImplementationFound($commonsImplementationList);

        return $poly->createExpectedObject($expected, $actual);
    }

    public function createExpectedObject($expectedClass, $actualObject)
    {
        //Clone for no change accessibility
        $workerActualObject = clone $actualObject;

        $refExpectedClass = new \ReflectionClass($expectedClass);
        $refActualClass = new \ReflectionClass($workerActualObject);
        $refPropertiesValue = $refExpectedClass->getProperties();

        $serializedProperties = array();

        $className = $refExpectedClass->getName();

        foreach ($refExpectedClass->getProperties() as $property) {

            $serializedPropertyName = $this->serializedPropertyName($property, $className);
            $serializedPropertyValue = serialize(null);

            if (null != $actualProperty = $refActualClass->getProperty($property->getName())) {
                $actualProperty->setAccessible(true);
                $serializedPropertyValue = serialize($actualProperty->getValue($actualObject));
            }

            $serializedProperties[] = sprintf('%s%s', $serializedPropertyName, $serializedPropertyValue);
        }

        $serializedExpectedObject = sprintf("O:%s:\"%s\":%s:{%s}", strlen($className), $className, count($refPropertiesValue),
                        implode("", $serializedProperties));

        return unserialize($serializedExpectedObject);
    }

    private function serializedPropertyName(\ReflectionProperty $property, $className)
    {
        $name = $property->getName();

        if ($property->isProtected()) {
            $name = chr(0) . "*" . chr(0) . $property->getName();
        } elseif ($property->isPrivate()) {
            $name = chr(0) . $className . chr(0) . $property->getName();
        }

        return serialize($name);
    }

    private function throwExceptionIfNotCommonsImplementationFound(array $commonsList)
    {
        if (count($commonsList) == 0) {
            throw new \InvalidArgumentException('Commons implemention not found');
        }
    }

    private function findCommonImplementation($leftListOfClass, $rightListOfClass)
    {
        return array_intersect($leftListOfClass, $rightListOfClass);
    }

    private function getImplementationList($class)
    {
        $reflectionClass = new \ReflectionClass($class);
        $interfacesList = $reflectionClass->getInterfaceNames();
        $extended = $reflectionClass->getParentClass();

        $list = $this->addSubClassName($extended, $interfacesList);

        return $list;
    }

    private function addSubClassName($extended, $interfacesList)
    {
        if ($extended) {
            $interfacesList[] = $extended->getName();
        }

        return $interfacesList;
    }
}
