<?php

namespace Nwidart\Menus\Presenters\Bootstrap;

class NavbarRightPresenter extends NavbarPresenter
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav navbar-nav navbar-right">' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        return '<li class="dropdown pull-right">
			      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					' . $item->getIcon() . ' ' . $item->title . '
			      	<b class="caret"></b>
			      </a>
			      <ul class="dropdown-menu">
			      	' . $this->getChildMenuItems($item) . '
			      </ul>
		      	</li>'
        . PHP_EOL;
    }
}
