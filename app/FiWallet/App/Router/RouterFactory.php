<?php

namespace FiWallet\App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory
{
    /**
     * @return \Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList();
        $router[] = new Route('recurrent-transactions/<presenter>[/<id>]', [
            'module' => 'Front:RecurrentTransactions',
            'presenter' => 'List',
            'action' => 'default',
        ]);
        $router[] = new Route('accounts/<presenter>[/<id>]', [
            'module' => 'Front:Accounts',
            'presenter' => 'List',
            'action' => 'default',
        ]);
        $router[] = new Route('filters/<presenter>[/<id>]', [
            'module' => 'Front:Filters',
            'presenter' => 'List',
            'action' => 'default',
        ]);
        $router[] = new Route('users/<presenter>[/<id>]', [
            'module' => 'Front:Users',
            'presenter' => 'Edit',
            'action' => 'default',
        ]);
        $router[] = new Route('statistics/<presenter>[/<id>]', [
            'module' => 'Front:Statistics',
            'presenter' => 'List',
            'action' => 'default',
        ]);
        $router[] = new Route('<presenter>[/<id>]', [
            'module' => 'Front',
            'presenter' => [Route::VALUE => 'Dashboard', Route::PATTERN => '((?!api).)*'],
            'action' => 'default',
        ]);
        return $router;
    }
}
