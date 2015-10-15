<?php

namespace AuthorizeNet\Hook\Back;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Back-office configuration hooks.
 */
class ConfigHook extends BaseHook
{
    /**
     * Render the module configuration page.
     * @param HookRenderEvent $event
     */
    public function onModuleConfiguration(HookRenderEvent $event)
    {
        $event->add($this->render('authorize-net-config.html'));
    }
}
