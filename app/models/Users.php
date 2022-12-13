<?php

namespace Models;

use Library\Core\ {Core, Database};
use Library\Misc\ {Cookie, Hash};

class Users extends Model {
        private $core;
        private $_db;

        public function __construct(){
            $this->_db = Database::getInstance();
            $this->core = new Core($this->_db);
        }

        /* Queries */

        public function getUserByEmail($email){
            $users = $this->_db->get("users", array("email", "=", $email));
            return $users;
        }

        public function getUsers() {
            $users = $this->_db->get("users", array("id", "<>", 0));
            return $users;
        }
    
        public function getUserByID($id) {
            $users = $this->_db->get("users", array("id", "<>", $id));
            return $users;
        }

        public function isUserHasPlan($id){
            try{
                $user_plan = $this->getSubscriptionByID($id);
                if($user_plan->status == 1){
                    return $user_plan ? true : false;
                }
            } catch(Throwable $e){  }
            return false;
        }

        public function isUserExists($id){
            foreach($this->getUsersArray() as $user){
                if((int)$user['id'] == $id){
                    return $user;
                }
            }
            return null;
        }
    }

?>