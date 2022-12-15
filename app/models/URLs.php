<?php

namespace Models;

use Library\Core\ {Database};
use Library\Misc\ {Cookie, Hash, Response};

class URLs extends Model {
        private $_db;

        public function __construct(){
            $this->_db = Database::getInstance();
        }

        public function generateURL($url){
            $response = new Response();
            $response->setMsg("");
            $response->setState(false);
            $response->setError("");
            if($this->isURLValid($url) != false){
                $newURL = $this->generateShortCode();
                if (!$this->_db->insert('urls', array(
                    'original' => $url,
                    'shortened' => $newURL
                ))) {
                    $response->setMsg('Something went wrong with the generating process.');
                    $response->setError('db_error');
                    $response->setState(false);
                } else {
                    $response->setMsg('The URL is generated successfully!');
                    $response->setError($_SERVER['SERVER_NAME'] . '/' . $newURL);
                    $response->setState(true);
                }
            } else {
                $response->setMsg("URL is invalid.");
                $response->setState(false);
                $response->setError("url_error");
            }
            return json_decode($response->render());
        }

        public function click($url){
            $click = $this->_db->increment("urls", $this->getIDByShort($url), 'clicks');
            return $click;
        }

        public function generateShortCode(){
            $hash = $this->generateHash();
            if($this->isShortCodeExists($hash)){
                $hash = $this->generateHash();
            }
            return $hash;
        }

        public function isShortCodeExists($hash){
            if($this->getURLByShort($hash)){
                return true;
            }
        }

        function generateHash(): string
        {
            $bytes = random_bytes(4);
            $base64 = base64_encode($bytes);

            return rtrim(strtr($base64, '+/', '-_'), '=');
        }

        public function getURLs() {
            $urls = $this->_db->get("urls", array("id", "<>", 0));
            return $urls;
        }

        public function getURLByID($id){
            $urls = $this->_db->get("urls", array("id", "=", $id));
            return $urls;
        }
    
        public function getURLByShort($short_url){
            $urls = $this->_db->get("urls", array("shortened", "=", $short_url));
            return $urls;
        }

        public function getURLByOriginal($url){
            $urls = $this->_db->get("urls", array("original", "=", $url));
            return $urls;
        }

        public function getIDByShort($short_url){
            $id = $this->_db->get("urls", array("shortened", "=", $short_url))->first();
            return $id->id;
        }
        
        public function getURLByAny($url){
            $urls = $this->_db->get("urls", array("original", "=", $url))->results();
            if(empty($urls)){
                $urls = $this->_db->get("urls", array("shortened", "=", $url))->results();
                if(empty($urls)){
                    return null;
                }
            }
            return $urls;
        }

        public function getClicksByURL($url){
            $urls = $this->getURLByShort($url)->first();
            if(empty($urls)){
                return null;
            }
            return $urls;
        }

        public function getClicks($url){
            $response = new Response();
            $response->setMsg("");
            $response->setState(false);
            $response->setError("");

            $clicks = $this->getClicksByURL($url);

            if(!$clicks){
                $response->setMsg("This code is not exists or available.");
                $response->setState(false);
                $response->setError("no-data");
            } else {
                $response->setMsg($clicks->clicks);
                $response->setState(true);
                $response->setError("");
            }
            return json_decode($response->render());
        }

        public function isURLExists($url){
            $urls = $this->getURLByAny($url);
            if(!$urls->count()){
                return false;
            }
            return true;
        }

        public function isURLValid($url){
            return filter_var($url, FILTER_VALIDATE_URL);
        }
    }

?>