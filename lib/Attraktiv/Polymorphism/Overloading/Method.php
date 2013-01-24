<?php
namespace Attraktiv\Polymorphism\Overloading;

/**
 * Overload Method implemented in userland
 * 
 * @author aurelien Fontaine <afontaine@efidev.com>
 */
abstract class Method
{
    private $overloadedMethod = array();

    public function __call($methodName, array $args)
    {    
        $methodsOverloaded = $this->compileClass($this);

        if (isset($methodsOverloaded[$methodName])) {
            return $this->tryCallMethodOverloaded($methodsOverloaded[$methodName], $args);
        }

        throw new BadMethodOverloadedException(sprintf("Method '%s' doesn't exist", $methodName));
    }

    private function compileClass($object)
    {
        if ($this->metaOverloadMethodNotLoaded()) {
            $reflectionClass = new \ReflectionClass($object);

            foreach ($reflectionClass->getMethods() as $method) {
                $this->findMetaOverloadMethod($method->getDocComment(), $method->getName());
            }
        }

        return $this->overloadedMethod;
    }
    
    private function metaOverloadMethodNotLoaded()
    {
        return 0 === count($this->overloadedMethod);
    }

    private function findMetaOverloadMethod($comment, $methodName)
    {
        if (preg_match("/@Overload\(method=\"(.+)\"/", $comment, $overloadedMethod)) {
            preg_match_all("/@param\s+([a-zA-Z0-9_]*)\s+/", $comment, $params);

            $this->overloadedMethod[$overloadedMethod[1]][] = array('params' => $params[1], 'method' => $methodName);
        }
    }

    private function getListOfTypeArgs(array $args)
    {
        $type = array();
        foreach ($args as $indexArg => $arg) {
            $type[] = $this->getTypeArg($arg);
        }

        return $type;
    }

    private function getListOfExtendedTypeArgs(array $args)
    {
        $type = array();
        foreach ($args as $indexArg => $arg) {
            if (gettype($arg) !== 'object') {
                $type[] = $this->getTypeArg($arg);
            } else {
                $refClass = new \ReflectionClass($arg);
                if (null != $parent = $refClass->getParentClass()) {
                    $type[] = $refClass->getParentClass()
                        ->getName();
                } else {
                    $type[] = get_class($arg);
                }
            }
        }

        return $type;
    }

    private function getListOfImplementedTypeArgs(array $args)
    {
        $type = array();
        $types = array();
        $replace = array();
        foreach ($args as $indexArg => $arg) {
            if (gettype($arg) !== 'object') {
                $type[] = $this->getTypeArg($arg);
            } else {
                $refClass = new \ReflectionClass($arg);
                $replace['arg' . $indexArg] = $refClass->getInterfaceNames();
                $type[] = 'arg' . $indexArg;
            }
        }

        if (count($replace) == 0) {
            $types[] = $type;
        }

        foreach ($replace as $key => $interfaces) {
            foreach ($interfaces as $interface) {
                $workType = $type;
                $keys = array_keys($workType, $key);
                $workType[$keys[0]] = $interface;
                $types[] = $workType;
            }
        }

        return $types;
    }

    private function getTypeArg($arg)
    {
        $type = gettype($arg);

        return $type == 'object' ? get_class($arg) : $type;
    }

    private function tryCallMethodOverloaded(array $methodsOverloaded, array $args)
    {
        foreach ($methodsOverloaded as $method) {
            if( null !== $methodResult = $this->callMethodByPriority($method, $args))
            {
                return $methodResult;
            }
        }
    }

    private function callMethodByPriority(array $method, array $args)
    {
        if ($this->callableMethodForSignatureClass($method, $args)) {
            return call_user_func_array(array($this, $method['method']), $args);
        }
        if ($this->callableMethodForExtendedClass($method, $args)) {
            return call_user_func_array(array($this, $method['method']), $args);
        }
        if ($this->callableMethodForImplementedClass($method, $args)) {
            return call_user_func_array(array($this, $method['method']), $args);
        }
    }

    private function callableMethodForSignatureClass(array $method, $args)
    {
        return $method['params'] == $this->getListOfTypeArgs($args);
    }

    private function callableMethodForExtendedClass(array $method, $args)
    {
        return $method['params'] == $this->getListOfExtendedTypeArgs($args);
    }

    private function callableMethodForImplementedClass(array $method, array $args)
    {
        foreach ($this->getListOfImplementedTypeArgs($args) as $type) {
            if ($method['params'] == $type) {
                return true;
            }
        }

        return false;
    }
}