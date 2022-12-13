<?php

namespace Library\Core;

class Core {
    private $_db;
    private $user;

    public function __construct($db){
        $this->_db = $db;
    }

    public function getDb(){
        return $this->_db;
    }

    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getSystem(){
        $system = $this->_db->get("system", array("id", "<>", 0));
        return $system;
    }

    public function getPlans(){
        $system = $this->_db->get("plans", array("id", "<>", 0));
        return $system;
    }

    public function stringBooleanToInt($stringBoolean){
        $_stringBoolean = 0;
        if($stringBoolean == 'true'){
            $_stringBoolean = 1;
        } else {
            $_stringBoolean = 0;
        }
        return $_stringBoolean;
    }

    public function formattedDate($date, $type){
        $timestamp = strtotime($date);
        if($type){
            // Long
            $part1 = date("l, F j, Y", strtotime($date));
            $part2 = " at ";
            $part3 = date("g:i a", strtotime($date));
        } else {
            // Short
            $part1 = date("F j", strtotime($date));
            $part2 = " at ";
            $part3 = date("g:i a", strtotime($date));
        }
        $newDate = $part1.$part2.$part3;
        return $newDate;
    }

    public function timeAgo($date) {
        $timestamp = strtotime($date);	
        
        $strTime = array("שניות", "דקות", "שעות", "ימים", "חודשים", "שנים");
        $length = array("60","60","24","30","12","10");
 
        $currentTime = time();
        if($currentTime >= $timestamp) {
            $diff = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);

            if($diff == 1){
                switch($strTime[$i]){
                    case "שניות":
                        return "לפני שנייה";
                        break;
                    case "דקות":
                        return "לפני דקה";
                        break;    
                    case "שעות":
                        return "לפני שעה";
                        break;
                    case "ימים":
                        return "אתמול";
                        break;
                    case "חודשים":
                        return "לפני חודש";
                        break;
                    case "שנים":
                        return "לפני שנה";
                        break;
                }
            }
            if($diff == 2){
                switch($strTime[$i]){
                    case "שעות":
                        return "לפני שעתיים";
                        break;
                    case "ימים":
                        return "לפני יומים";
                        break;
                    case "חודשים":
                        return "לפני חודשיים";
                        break;
                    case "שנים":
                        return "לפני שנתיים";
                        break;
                }
            }
            return "לפני " . $diff . " " . $strTime[$i];
        }
    }
}