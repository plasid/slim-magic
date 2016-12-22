<?php

/**
 * Slim Magic Mapper
 *
 * @link      https://github.com/atlantic8-web/slim-magic
 * @copyright Copyright (c) 2011-2016 Christof Coetzee
 * @license   https://github.com/atlantic8-web/slim-magic/blob/master/LICENSE (MIT License)
 */

namespace SlimMagic;

class Mapper
{

    /**
     * SlimMagic Container
     * @var type \SlimMagic\ServiceContainer
     */
    protected $smc;

    /**
     *
     * @var array
     */
    protected $middlewareLoaded;
    protected $serviceLoaded;

    public function __construct(\SlimMagic\ServiceContainer $container)
    {

        $this->smc = $container;
        $this->assemble();
    }

    private function assemble()
    {

        try {

            foreach ($this->smc->getConfig()['routes'] as $route => $spec) {

                $ins = $this->smc->getSlim()->map($spec['methods'], (string) $route, (string) $spec['classmap']);
                
                !empty($spec['name']) ? $ins->setName($spec['name']) : null;
                !empty($spec['arguments']) ? $ins->setArguments($spec['arguments']) : null;

                $this->debug('Add route: ' . (string) $route);

                foreach ($spec['middleware'] as $m) {
                    $ins->add($this->getMiddleware($m));
                }
            }

            $this->setAllMiddleware();
            $this->setAllService();
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function setAllService()
    {
        foreach ($this->smc->getConfig()['all']['service'] as $c) {

            $this->debug('Add service: \service\Dependency\\' . $c . ':set');

            $ss = '\service\Dependency\\' . $c;
            $so = new $ss();
            $so->set($this->smc->getSlimContainer());
        }
    }

    private function setAllMiddleware()
    {
        foreach ($this->smc->getConfig()['all']['middleware'] as $c) {
            $this->smc->getSlim()->add($this->getMiddleware($c));
        }
    }

    private function getMiddleware($m)
    {
        $this->debug('Add middleware: ' . $m);
        return '\service\Middleware\\' . $m;
    }

    private function debug($msg)
    {
        if ($this->smc->getConfig()['debug']) {
            echo $msg . "\n";
        }
    }

}
