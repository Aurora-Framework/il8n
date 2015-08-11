<?php

namespace Aurora;

use Aurora\AdapterInterface;
use Aurora\Helper\ObjectTrait;

class il8n
{
   use ObjectTrait;

   protected $Adapter;

   public $language = "en_UK";
   public $fallbackLanguage = "en_US";
   public $useLanguage;

   public function __construct(
      AdapterInterface $Adapter,
      $language = "en_UK",
      $fallbackLanguage = "en_US"
   ) {
      $this->Adapter = $Adapter;
      $this->language = $language;
      $this->fallbackLanguage = $fallbackLanguage;
   }

   public function setLocale($language)
   {
      if (file_exists($this->Adapter->basePath."/".$language."/")) {
         $this->useLanguage = $language;
      } else if (file_exists($this->Adapter->basePath."/".$this->fallbackLanguage."/")) {
         $this->useLanguage = $this->fallbackLanguage;
      } else {
         throw new LanguageFileNotFoundException("Language: ${language} wasn't found");
      }
   }

   /**
   * Get value assigned for key
   *
   * @access public
   * @param string|int $key Identifier for $data
   * @return mixed|null Value given for key
   */
   public function get($key, $default = null, ...$arguments)
   {
      $return = $this->data;

      if (!empty((string) $key)) {

         $keys = explode('.', $key);

         foreach ($keys as $key) {
            if (isset($return[(string) $key])) {
               $return = $return[$key];
            } else {
               $return = $default;
            }
         }
      }

      $return = preg_replace_callback('/(\:\#(\S+))/', function($match) use(&$arguments) {
         return $this->get($match[2], null, array_shift($arguments));
      }, $return);

      return preg_replace_callback('/(\:[^\#]\S+)/', function($match) use(&$arguments) {
         return array_shift($arguments);
      }, $return);

   }

   public function setPath($path)
   {
      $this->Adapter->setBasePath($path);
   }

   public function setAdapter(AdapterInterface $Adapter)
   {
      $this->Adapter = $Adapter;
   }

   public function setLanguage($language)
   {
      $this->language = $language;
   }

   public function getLanguage()
   {
      return $this->language;
   }

   public function setFallbackLanguage($language)
   {
      $this->fallbackLanguage = $language;
   }

   public function getFallbackLanguage()
   {
      return $this->fallbackLanguage;
   }

   public function withFile($file, $bind = null)
   {
      $bind = ($bind === null) ? $file : $bind;
      $this->data[$bind] = $this->Adapter->loadFile($this->useLanguage."/".$file);
   }

   public function withData($data)
   {
      $this->data = $this->Adapter->load($data);
   }

   public function withDataArray($data)
   {
      $this->connect($data);
   }

   public function withFiles($files = [])
   {
      foreach ($files as $key => $value) {
         $key = (is_int($key)) ? $value : $key;
         $this->data[$key] = $this->Adapter->loadFile($this->useLanguage."/".$value);
      }
   }
}
