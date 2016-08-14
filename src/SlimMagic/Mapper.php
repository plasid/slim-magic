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

    protected $container;

    public function __construct(\SlimMagic\ServiceContainer $container)
    {

        $this->container = $container;
        $this->assemble();
    }

    private function assemble()
    {

        try {

            $slimContainer = $this->container->getSlim()->getContainer();

            foreach ($this->container->getConfig()['all']['service'] as $c) {
                call_user_func(['\service\Dependency\\' . $c, 'set'], $slimContainer);
            }

            foreach ($this->container->getConfig()['routes'] as $route => $spec) {

                $ins = $this->container->getSlim()->map($spec['methods'], (string) $route, (string) $spec['classmap']);
                !empty($spec['name']) ? $ins->setName($spec['name']) : null;
                !empty($spec['arguments']) ? $ins->setArguments($spec['arguments']) : null;

                foreach ($spec['middleware'] as $m) {
                    $ins->add(call_user_func(['\service\Middleware\\' . $m, 'get'], $slimContainer));
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
