<?php

namespace Nwidart\Menus\Presenters\Bootstrap;

class NavPillsPresenter extends NavbarPresenter
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav nav-pills">' . PHP_EOL;
    }
}
