<?php

namespace Nwidart\Menus\Presenters\Bootstrap;

use Nwidart\Menus\Presenters\Presenter;

class SidebarMenuPresenter extends Presenter
{
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
        return '<ul class="nav navbar-nav">';
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
        return '</ul>';
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        return '<li' . $this->getActiveState($item) . '>
			<a href="' . $item->getUrl() . '" ' . $item->getAttributes() . '>'
        . $item->getIcon() . ' ' . $item->title . '</a></li>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = ' class="active"')
    {
        return $item->isActive() ? $state : null;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getActiveStateOnChild($item, $state = 'active')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * {@inheritdoc }.
     */
    public function getDividerWrapper()
    {
        return '<li class="divider"></li>';
    }

    /**
     * {@inheritdoc }.
     */
    public function getHeaderWrapper($item)
    {
        return '<li class="dropdown-header">' . $item->title . '</li>';
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        $id = str_random();

        return '
		<li class="' . $this->getActiveStateOnChild($item) . ' panel panel-default" id="dropdown">
			<a data-toggle="collapse" href="#' . $id . '">
				' . $item->getIcon() . ' ' . $item->title . ' <span class="caret"></span>
			</a>
			<div id="' . $id . '" class="panel-collapse collapse ' . $this->getActiveStateOnChild($item, 'in') . '">
				<div class="panel-body">
					<ul class="nav navbar-nav">
						' . $this->getChildMenuItems($item) . '
					</ul>
				</div>
			</div>
		</li>
		' . PHP_EOL;
    }

    /**
     * Get multilevel menu wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string`
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        return $this->getMenuWithDropDownWrapper($item);
    }
}
