<?php
/**
 * Created by PhpStorm.
 * User: cerynna
 * Date: 16/12/17
 * Time: 18:19
 */

namespace MasterHook;


class User
{

    public $id;

    public $last_use;

    public $last_action;

    public $prev_action;

    public $geoloc;

    /**
     * @return mixed
     */
    public function getPrevAction()
    {
        return $this->prev_action;
    }

    /**
     * @param mixed $prev_action
     * @return User
     */
    public function setPrevAction($prev_action)
    {
        $this->prev_action = $prev_action;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastUse()
    {
        return $this->last_use;
    }

    /**
     * @param mixed $last_use
     * @return User
     */
    public function setLastUse($last_use)
    {
        $this->last_use = $last_use;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastAction()
    {
        return $this->last_action;
    }

    /**
     * @param mixed $last_action
     * @return User
     */
    public function setLastAction($last_action)
    {
        $this->last_action = $last_action;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGeoloc()
    {
        return $this->geoloc;
    }

    /**
     * @param mixed $geoloc
     * @return User
     */
    public function setGeoloc($geoloc)
    {
        $this->geoloc = $geoloc;
        return $this;
    }



    public function getUser()
    {
        return $this;
    }

    public function __construct($user)
    {
        if (!empty($user['id'])) {
            $this->setId($user['id']);
        }
        if (!empty($user['last_use'])){
            $this->setLastUse($user['last_use']);
        }
        if (!empty($user['last_action'])){
            $this->setLastAction($user['last_action']);
        }
        if (!empty($user['geoloc'])){
            $this->setGeoloc($user['geoloc']);
        }
        return $this;

    }


}