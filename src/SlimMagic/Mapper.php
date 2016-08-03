<?php

namespace SlimMagic;

class Mapper
{

    protected $slimApp;
    protected $config;

    public function __construct($slimApp, $config)
    {
        $this->slimApp = $slimApp;
        $this->config = $config;
        $this->assemble();
    }

    public function getSlim()
    {
        return $this->slimApp;
    }

    private function assemble()
    {

        try {

            foreach ($this->config['routes'] as $route => $spec) {
                
                $sn = $this->routeSpecNormalize($spec);
                $ins = $this->slimApp->map($sn['methods'], $route, $sn['classmap']);
                !empty($sn['name']) ? $ins->setName($sn['name']) : null;
                !empty($sn['arguments']) ? $ins->setArguments($sn['arguments']) : null;
                foreach ($sn['middleware'] as $m) {
                    $ins->add(new $m);
                }
            }
            
            $this->slimApp->run();
            
        } catch (\Exception $e) {
           throw $e;
        }
    }

    private function dashToCamel($str, $isMethod = false)
    {
        $ret = implode('', array_map('ucwords', explode('-', $str)));
        return $isMethod ? lcfirst($ret) : $ret; //PSR2
    }

    private function routeSpecNormalize($route)
    {
        $default = [
            'methods' => ['GET'],
            'classmap' => 'routeNotDefined',
            'middleware' => [],
            'arguments' => [],
            'name' => ''
        ]; 
        
        $route = empty($route['methods']) ? ['GET'] : array_map('strtoupper', (array)$route['methods']);
        
        return array_merge($default, $route);
    }

}
