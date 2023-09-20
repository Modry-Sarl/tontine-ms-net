<?php

namespace App\Database\Seeds;

use App\MS\Register;
use BlitzPHP\Contracts\Database\ConnectionResolverInterface;
use BlitzPHP\Database\Connection\BaseConnection;
use BlitzPHP\Database\Seeder\Seeder;
use BlitzPHP\Wolke\Model;

class InitialUser extends Seeder
{
    public function __construct(BaseConnection $db) 
    {
        parent::__construct($db);
        Model::setConnectionResolver(service(ConnectionResolverInterface::class));
    }
    
    public function run()
    {
        $register = new Register([
            'email' => 'admin@tontinemsnet.com',
            'tel' => '677889900',
            'pays' => 'cm',
        ], 'password');

        $register->process();
    }
}
