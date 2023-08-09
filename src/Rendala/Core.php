<?php

namespace Rendala;

require_once __DIR__.'/../../vendor/autoload.php';

use Rendala\Core\App;

class Core
{
  const VERSION             = '1.0';

  const DEFAULT_APP         = 'default';

  const DS                  = DIRECTORY_SEPARATOR;

  /* ENVIRONMENT CONSTANT */

  const ENV_PROD            = 'prod';

  const ENV_TEST            = 'test';

  const ENV_DEV             = 'dev';


  /* DATABASE CONSTANT */

  const DB_CNX_DEFAULT      = 'slave';

  const DB_CNX_MASTER       = 'master';

  const DB_CNX_SLAVE        = 'slave';

  const CONF_DSN            = 'db_dsn';

  /* PATHS */

  const PATH_APPS           = 'apps';

  const PATH_CORE           = 'src';

//   const PATH_MODELS         = 'models';

  const PATH_CONF           = 'config';

  const PATH_TMP            = 'tmp';

  const PATH_TESTS          = 'tests';

  const PATH_PUBLIC         = 'public';

//   const PATH_DATA           = 'data';

//   const PATH_DEPLOY         = 'deploy';

//   const PATH_MIGRATIONS     = 'migrations';

//   const PATH_MODULE_TEMPLATES = 'templates';

  const CONFIG_FILE         = 'config.php';


  /**
   * Path of the library
   *
   */
  private static $_path;

  private static $_app;

  private static $_env;

  public function __construct()
  {
    throw new Core\Exception('Core is static class. No instances can be created');
  }

  public static function getPath()
  {
    if (!self::$_path) {
      self::$_path = realpath(dirname(__FILE__) . '/../../');
    }
    return self::$_path;
  }

  public static function autoload($className)
  {
    if (class_exists($className, false) || interface_exists($className, false)) {
      return false;
    }
    $class_path = str_replace('\\', '/', $className);
    $file =  self::getPath() . self::DS . self::PATH_CORE . self::DS . $class_path . '.php';

    if (file_exists($file)) {
      require $file;
      return true;
    }

    // // if not detect if it's a model
    // $class = self::getPath() . self::DS . self::PATH_MODELS . self::DS . $className . '.php';
    // if (file_exists($class)) {
    //   require $class;
    //   return true;
    // }

    // // if not detect if it's a base model
    // $class = self::getPath() . self::DS . self::PATH_MODELS . self::DS . 'generated' . self::DS . $className . '.php';
    // if (file_exists($class)) {
    //   require $class;
    //   return true;
    // }

    // if not, detect if it's a personal lib
    // $class = self::getPath() . self::DS . self::PATH_APPS . self::DS . self::$_app . self::DS . self::PATH_LIBS . self::DS . $className . '.php';
    // if (file_exists($class)) {
    //   require $class;
    //   return true;
    // }
    return false;
  }

  public static function getEnv()
  {
    return self::$_env;
  }

  public static function setEnv($env = self::ENV_PROD)
  {
    self::$_env = $env;
  }

  private static function _initApp($application)
  {
    if (isset($_SERVER['APP_NAME'])) {
      self::$_app = $_SERVER['APP_NAME'];
    }
    else {
      self::$_app = $application;
    }
  }

  public static function initEnv($env = self::ENV_PROD)
  {
    if (isset($_SERVER['APP_ENV'])) {
      self::setEnv($_SERVER['APP_ENV']);
    }
    else {
      self::setEnv($env);
    }
    switch (self::getEnv()) {
      case self::ENV_DEV:
      case self::ENV_TEST:
        error_reporting(E_ALL & ~(E_DEPRECATED | E_STRICT | E_NOTICE));
        ini_set('display_errors', '1');
        break;
      default:
        error_reporting(E_ALL & ~(E_DEPRECATED | E_STRICT | E_NOTICE));
        ini_set('display_errors', 0);
    }
    ini_set('magic_quotes_gpc', false);
    ini_set('register_globals', false);
    ini_set('session.cookie_httponly', true);
  }

//   public static function loadConf()
//   {
//     switch (self::getEnv()) {
//       case self::ENV_TEST:
//         $dotenv = Dotenv\Dotenv::createMutable(self::getPath(), '.env.test');
//         break;
//       default:
//         $dotenv = Dotenv\Dotenv::createMutable(self::getPath());
//     }
//     $dotenv->load();
//     $config_path = self::getPath() . self::DS . self::PATH_CONF . self::DS . self::CONFIG_FILE;
//     if (Ea_File::exist($config_path)) {
//       include($config_path);
//     }
//   }

//   public static function loadModel()
//   {
//     $class = self::getPath() . self::DS . self::PATH_LIBS . self::DS . 'Doctrine.php';
//     require_once($class);
//     set_include_path(get_include_path() . PATH_SEPARATOR .
//                      self::getPath() . self::DS . self::PATH_MODELS . self::DS . 'generated');
//     spl_autoload_register(array('Doctrine_Core', 'autoload'));
//     spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

//     foreach (Ea_Config::get(self::CONF_DSN) as $name => $dsn) {
//       Doctrine_Manager::connection($dsn, $name);
//       Doctrine_Manager::connection()->setCharset('utf8mb4');
//       Doctrine_Manager::connection()->setCollate('utf8mb4_unicode_ci');
//       Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
//       Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
//     }
//     $manager = Doctrine_Manager::getInstance();
//     $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
//     $manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
//     if ((self::getEnv() === self::ENV_PROD) && $_SERVER['CACHE_ALLOW'] && class_exists('Memcache')) {
//       // Allow Query Cache
//       $memcache = array('host' => $_SERVER['CACHE_HOST'], 'port' => $_SERVER['CACHE_PORT'], 'persistent' => true);
//       $cacheDriver = new Doctrine_Cache_Memcache(array('servers' => $memcache, 'compression' => false, 'prefix' => __DIR__ . '-' . self::$_app . '-' . self::$_env . '-'));
//       $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
//       $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cacheDriver);
//       $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 3600);
//     }
//     Doctrine_Core::loadModels(self::getPath() . self::DS . self::PATH_MODELS);
//   }

//   public static function loadFixtures($path)
//   {
//     Doctrine_Core::loadData($path, false);
//   }

  public static function runWeb($application = self::DEFAULT_APP, $env = self::ENV_PROD)
  {
    self::initEnv($env);
    self::_initApp($application);
    // self::loadConf();
    // self::loadModel();
    try {
      App::runWeb(self::$_app);
    }
    catch (App\Exception $e) {
      echo $e->display();
      exit();
    }
  }

  // public static function runAPI($application = self::DEFAULT_APP, $env = self::ENV_PROD)
  // {
  //   self::initEnv($env);
  //   self::_initApp($application);
  //   self::loadConf();
  //   self::loadModel();
  //   try {
  //     Core_Api::run(self::$_app);
  //   }
  //   catch (Core_App_Exception $e) {
  //     echo $e->display();
  //   }
  //   exit();
  // }

  // public static function runConsole($arguments, $env = self::ENV_DEV)
  // {
  //   self::initEnv($env);
  //   self::loadConf();
  //   self::loadModel();
  //   try {
  //     $console = new Ea_Console();
  //     $console->run($arguments);
  //   }
  //   catch (Ea_Console_Exception $e) {
  //     echo $e->display();
  //     exit();
  //   }
  // }

  // public static function initCron($env = self::ENV_PROD)
  // {
  //   self::initEnv($env);
  //   self::loadConf();
  //   self::loadModel();
  // }

  // public static function initTest()
  // {
  //   self::initEnv(self::ENV_TEST);
  //   self::loadConf();
  //   self::loadModel();
  // }

  // public static function truncateDatabase()
  // {
  //   $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
  //   $dbh = $connection->getDbh();
  //   $dbh->query(sprintf('SET FOREIGN_KEY_CHECKS = 0;'));
  //   $tables = $connection->import->listTables();
  //   foreach ($tables as $table) {
  //     $sql = sprintf('TRUNCATE TABLE `%s`', $table);
  //     $dbh->query($sql);
  //   }
  //   $dbh->query(sprintf('SET FOREIGN_KEY_CHECKS = 1;'));
  //   unset($dbh);
  // }

  // public static function t()
  // {
  //   $args = func_get_args();

  //   if (count($args) < 1) {
  //     return;
  //   }

  //   $text = array_shift($args);

  //   global $__t;

  //   if (isset($__t[$text])) {
  //     return vsprintf($__t[$text], $args);
  //   }
  //   return vsprintf($text, $args);
  // }

  // public static function rollback($env)
  // {
  //   require_once(Ea::getPath() . Ea::DS . Ea::PATH_CONF . Ea::DS . 'deploy.php');

  //   $info = $deploy[$env];
  //   // get the la
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -f ' . $info['path'] . 'current && ln -s ' . $info['path'] . 'releases/`/usr/bin/ssh ' . $info['server'] . ' \"ls -rt --color=never ' . $info['path'] . 'releases/ | sort -u | head -2 | tail -1"` ' . $info['path'] . 'current"');
  //   // delete last version
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -rf ' . $info['path'] . 'releases/`/usr/bin/ssh ' . $info['server'] . ' \"ls -rt --color=never ' . $info['path'] . 'releases/ | sort -u | tail -1"`"');
  // }

  // public static function deploy($env, $migrate = false)
  // {
  //   require_once(Ea::getPath() . Ea::DS . Ea::PATH_CONF . Ea::DS . 'deploy.php');

  //   $info = $deploy[$env];
  //   $release_name = date('YmdHis');

  //   // create temp dir with
  //   echo '-> Create new release [' . $release_name. ']' . PHP_EOL;

  //   echo '-> Create the directory structure' . PHP_EOL;
  //   // create the default directories on the server
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "mkdir -p ' . $info['path'] . 'releases; mkdir -p ' . $info['path'] . 'shared; mkdir -p ' . $info['path'] . 'shared/tmp/; mkdir -p ' . $info['path'] . 'shared/web/; mkdir -p ' . $info['path'] . 'shared/config/"');
  //   echo '----------------' . PHP_EOL;

  //   // clone the git reposory
  //   echo '-> Clone git Repository' . PHP_EOL;
  //   //exec('git clone --depth 1 --recursive -q ' . $info['repository'] . ' ' . $path);
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "git clone --depth 1 --recursive -q ' . $info['repository'] . ' ' . $info['path'] . Ea::DS . 'releases' . Ea::DS  . $release_name . '"');
  //   echo '----------------' . PHP_EOL;

  //   /*echo '-> Copy data to server' . PHP_EOL;
  //   // rsync the data
  //   exec('/usr/bin/rsync -azvu ' . $path . ' --exclude .svn --exclude .git --exclude .gitignore --exclude .gitmodules --exclude docs --exclude composer.json --exclude composer.lock --exclude deploy ' . $info['server'] . ':' . $info['path'] . Ea::DS . 'releases');
  //   echo '----------------' . PHP_EOL;*/

  //   echo '-> Removing Not needed directories' . PHP_EOL;
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -rf ' . $info['path'] . '/releases/' . $release_name . '/docs"');
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -rf ' . $info['path'] . '/releases/' . $release_name . '/data/fixtures"');
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -rf ' . $info['path'] . '/releases/' . $release_name . '/tests"');
    

  //   // link the config folder to the shared one
  //   echo '-> Create symlinks' . PHP_EOL;
  //   // link the tmp folder to the shared one
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "ln -s  ' . $info['path'] . 'shared/tmp/ ' . $info['path'] . '/releases/' . $release_name . '/tmp"');
  //   // link the config folder to the shared one
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -rf ' . $info['path'] . '/releases/' . $release_name . '/config ; ln -s  ' . $info['path'] . 'shared/config/ ' . $info['path'] . '/releases/' . $release_name . '/config"');
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -f ' . $info['path'] . '/releases/' . $release_name . '/.env ; ln -s  ' . $info['path'] . 'shared/.env ' . $info['path'] . '/releases/' . $release_name . '/.env"');

  //   // link all uploads folders into the shared one
  //   clearstatcache();
  //   $web_path = self::getPath() . self::DS . self::PATH_WEB . self::DS;

  //   if (($dir = opendir($web_path))) {
  //     while (($file = readdir($dir)) !== false) {
  //       if (($file !== '.') && ($file !== '..') && is_dir($web_path . '/' . $file)) {
  //         exec('/usr/bin/ssh ' . $info['server'] . ' "mkdir -p ' . $info['path'] . 'shared/web/' . $file . '/upload; ln -s  ' . $info['path'] . 'shared/web/' . $file . '/upload ' . $info['path'] . '/releases/' . $release_name . '/web/' . $file . '/upload"');
  //       }
  //     }
  //     closedir($dir);
  //   }
  //   echo '----------------' . PHP_EOL;

  //   echo '-> Setting up user rights' . PHP_EOL;
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "chown -R www-data:www-data ' . $info['path'] . 'releases/' . $release_name . '"');
  //   echo '----------------' . PHP_EOL;

  //   if ($migrate == true) {
  //     echo '-> Migrate Database' . PHP_EOL;
  //     // do the migration if need be
  //     exec('/usr/bin/ssh ' . $info['server'] . ' "cd ' . $info['path'] . '/releases/' . $release_name . '; ./ea doctrine:migrate force; ./ea doctrine:generate:models"');
  //     echo '----------------' . PHP_EOL;
  //   }

  //   echo '-> Setting up user rights' . PHP_EOL;
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "chown -R www-data:www-data ' . $info['path'] . 'releases/' . $release_name . '"');
  //   echo '----------------' . PHP_EOL;

  //   echo '-> Cleaning old releases' . PHP_EOL;
  //   // only keep 3 releases
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "cd ' . $info['path'] . 'releases/ && ls -t --color=\"never\" | awk ' . '\'' . 'NR>3' . '\'' . ' | xargs rm -r"');
  //   echo '----------------' . PHP_EOL;
  //   // link to current
  //   exec('/usr/bin/ssh ' . $info['server'] . ' "rm -f ' . $info['path'] . 'current && ln -s ' . $info['path'] . 'releases/' . $release_name . ' ' . $info['path'] . 'current"');
  //   // delete temp path
  //   //Ea_Dir::delete($path);
  //   if (isset($info['post-command'])) {
  //     echo '-> Execute Post-Command [' . $info['post-command'] . ']'  . PHP_EOL;
  //     // execute post command
  //     exec('/usr/bin/ssh ' . $info['server'] . ' "' . $info['post-command'] . '"');
  //     echo '----------------' . PHP_EOL;
  //   }
  // }

  // public static function deployAssets($app, $version, $env)
  // {
  //   self::initEnv($env);
  //   self::_initApp($app);
  //   self::loadConf();
  //   self::loadModel();
  //   try {
  //     Ea_App::deployAssets(self::$_app, $version);
  //   }
  //   catch (Ea_App_Exception $e) {
  //     echo $e->display();
  //     exit();
  //   }
  // }

  // public static function launchTest($args)
  // {
  //   $out = array();
  //   exec(self::getPath() . self::DS . 'vendor/bin/codecept ' . $args, $out);
  //   foreach ($out as $line) {
  //     echo $line . PHP_EOL;
  //   }
  // }
}

spl_autoload_register(array('Rendala\Core', 'autoload'));