<?php

namespace Nwidart\Menus\Presenters\Foundation;

use Nwidart\Menus\Presenters\Presenter;

class ZurbMenuPresenter extends Presenter
{
    /**
     * {@inheritdoc }
     */
    public function getOpenTagWrapper()
    {
        return  PHP_EOL . '<nav class="custom-main">
        <ul class="dropdown menu" data-dropdown-menu>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }
     */
    public function getCloseTagWrapper()
    {
        return  PHP_EOL . '</ul></nav>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        return '<li'.$this->getActiveState($item).'><a href="'. $item->getUrl() .'">'.$item->title.'</a></li>';
    }

    /**
     * {@inheritdoc }
     */
    public function getActiveState($item)
    {
        return \Request::is($item->getRequest()) ? ' class="is-active"' : null;
    }

    /**
     * {@inheritdoc }
     */
    public function getDividerWrapper()
    {
        return '<li class="divider"></li>';
    }

    /**
     * {@inheritdoc }
     */
    public function getMenuWithDropDownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }

        return '<li class="dropdown dropdown-primary">
                    <a class="dropdown-toggle" href="#">'.$item->title.'</a>
                    <ul class="menu">
                      '.$this->getChildMenuItems($item).'
                    </ul>
                </li>' . PHP_EOL;
    }


    /**
     * {@inheritdoc }
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        if($item->auth && auth()->guest()) {
            return null;
        }
        
        return '<li>
                  <a href="#">'.$item->title . '</a>
                  <ul class="menu">
                    ' . $this->getChildMenuItems($item) . '
                  </ul>
                </li>'. PHP_EOL;
    }
}