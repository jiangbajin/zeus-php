<?php
/**
 * User: nathena
 * Date: 2017/6/19 0019
 * Time: 16:01
 */

namespace booking;


use zeus\domain\AggregateRoot;

class Order extends AggregateRoot
{
    protected $schema = "t_order";

    private $store_engine;

    public function __construct($data = null)
    {
        parent::__construct($data);

        $this->store_engine = OrderRepository::getInstance();
    }
}