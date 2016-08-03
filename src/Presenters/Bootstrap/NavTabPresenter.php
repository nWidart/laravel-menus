<?php

namespace Nwidart\Menus\Presenters\Bootstrap;

class NavTabPresenter extends NavbarPresenter
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav nav-tabs">' . PHP_EOL;
    }
}
