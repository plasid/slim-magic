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
    protected $middlewareLoaded;

    public function __construct(\SlimMagic\ServiceContainer $container)
    {

        $this->smc = $container;
        $this->assemble();
    }

    private function assemble()
    {

        try {

            $slimContainer = $this->smc->getSlim()->getContainer();

            foreach ($this->smc->getConfig()['all']['service'] as $c) {
                if ($this->smc->getConfig()['debug']) {
                    echo 'Add service: \service\Dependency\\' . $c . ':set' . "\n";
                }
                call_user_func(['\service\Dependency\\' . $c, 'set'], $slimContainer);
            }

            $this->setAllMiddleware(0);

            foreach ($this->smc->getConfig()['routes'] as $route => $spec) {

                $ins = $this->smc->getSlim()->map($spec['methods'], (string) $route, (string) $spec['classmap']);
                !empty($spec['name']) ? $ins->setName($spec['name']) : null;
                !empty($spec['arguments']) ? $ins->setArguments($spec['arguments']) : null;

                if ($this->smc->getConfig()['debug']) {
                    echo 'Add route: ' . (string) $route . "\n";
                }

                foreach ($spec['middleware'] as $m) {
                    $ins->add($this->getMiddleware($m));
                }
            }

            $this->setAllMiddleware(1);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function setAllMiddleware($o)
    {
        if ($this->smc->getConfig()['all']['middleware_order'] != $o) {
            return false;
        }

        foreach ($this->smc->getConfig()['all']['middleware'] as $c) {
            $this->smc->getSlim()->add($this->getMiddleware($c));
        }
    }

    private function getMiddleware($m)
    {
        if (!isset($this->middlewareLoaded[$m])) {
            $mp = '\service\Middleware\\' . $m;
            $this->middlewareLoaded[$m] = call_user_func([$mp, 'get'], $this->smc->getSlim()->getContainer());
            if ($this->smc->getConfig()['debug']) {
                echo 'Add middleware: ' . $mp . ':get' . "\n";
            }
        }

        return $this->middlewareLoaded[$m];
    }

}
