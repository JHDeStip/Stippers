<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 3/12/2014
 * Time: 13:44
 */

class AuthorizedBrowser {
    public $uuid;
    public $name;
    public $canAddUpdateUsers;
    public $canCheckIn;

    public function __construct($uuid, $name, $canAddUpdateUsers, $canCheckIn) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->canAddUpdateUsers = $canAddUpdateUsers;
        $this->canCheckIn = $canCheckIn;
    }
}