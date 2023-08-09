<?php

namespace Rendala\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

use function Symfony\Component\String\u;

use Rendala\Core;

class App
{
  const PATH_APP_CONFIG       = 'config';
  
//   const PATH_APP_LIBS         = 'libs';
  
  const PATH_APP_APP      = 'app';
  
//   const PATH_APP_TEMPLATES    = 'templates';
  
//   const PATH_APP_LOCALE       = 'locale';
  
  const CONFIG_FILE           = 'config.php';
  
  const ROUTES_FILE           = 'routes.php';
  
  const ACTIONS_FILE          = 'actions.php';
  
  protected static $_path;
  
  protected static $_user;
  
  public static function initPath($name)
  {
    self::$_path = Core::getPath() . Core::DS . Core::PATH_APPS . Core::DS . $name;
  }
  
  protected static function _loadConf()
  {
    $config_path = self::$_path . Core::DS . self::PATH_APP_CONFIG . Core::DS . self::CONFIG_FILE;
    if (file_exists($config_path)) {
      include($config_path);
    }
  }
  
  protected static function _loadRoutes()
  {
    $routes_path = self::$_path . Core::DS . self::PATH_APP_CONFIG . Core::DS . self::ROUTES_FILE;
    if (file_exists($routes_path)) {
      include($routes_path);
      // $rcollection = new RoutingConfigurator();
      foreach ($routes as $name => $route) {
        dump($route);
        // $r = new Route($route['url'], isset($route['default']) ? $route['default'] : []);
        // if (isset($route['controller'])) {
        //     $actions_file = self::$_path . Core::DS . self::PATH_APP_APP . Core::DS . $route['controller'] . Core::DS . self::ACTIONS_FILE;
        //     if (file_exists($actions_file)) {
        //         require_once($actions_file);
        //         $classname = u($route['controller'])->camel() . 'Controller';
        //         $r->controller([$classname . '::class', $route['action']]);
        //     }
        // }
        // $rcollection->add($name, $r);
      }
      return false;
      //return $rcollection;
    }
  }
  
  private static function _runRequest($request, $matcher)
  {
    dump($request);
    dump($matcher);
    
    // if (isset($request['module']) && isset($request['action'])) {
    
    //   $actions_file = self::$_path . Ea::DS . self::PATH_APP_MODULES . Ea::DS . $request['module'] . Ea::DS . self::ACTIONS_FILE;
    //   if (Ea_File::exist($actions_file)) {
    //     require_once($actions_file);
    //     $classname = $request['module'] . 'Actions';
    //     $ctrl = new $classname(self::$_path);
    //     if (!method_exists($ctrl, $request['action'])) {
    //       self::_handle404($request);
    //     }
    //     $ctrl->initialize($request);
    //     $ctrl->beforeAction($request);
    //     $res = $ctrl->{$request['action']}($request);
    //     $ctrl->afterAction();
    //     if ($res !== Ea_Controller::VIEW_NONE) {
    //       $ctrl->renderView();
    //     }
    //   }
    //   else {
    //     self::_handle404($request);
    //   }
    // }
  }
  
//   private static function _handle404($request)
//   {
//     header("HTTP/1.0 404 Not Found");
//     $ctrl = new Ea_Controller_404(self::$_path);
//     $ctrl->initialize($request);
//     $ctrl->index($request);
//     $ctrl->renderView();
//     exit();
//   }
  
//   protected static function _loadUser()
//   {
//     // set the include_path to the admin
//     $app_lib_path = self::$_path . Ea::DS . self::PATH_APP_LIBS;
//     set_include_path(get_include_path() . Ea::DS . $app_lib_path);
    
//     // include the user class if it exist                  
//     if (Ea_File::exist($app_lib_path  . Ea::DS . 'myUser.php')) {
//       include($app_lib_path  . Ea::DS . 'myUser.php');
//       myUser::initialize();
//     }
//     else {
//       Ea_User::initialize();
//     }
//   }
  
//   protected static function _loadTranslation()
//   {
//     $path = self::$_path . Ea::DS . self::PATH_APP_LOCALE;
//     $culture = Ea_Config::get('store_view_id');
//     if (Ea_File::exist($path . Ea::DS . $culture . '.php')) {
//       include($path . Ea::DS . $culture . '.php');
//     }
//   }
  
  public static function runWeb($name = Core::DEFAULT_APP)
  {
    // check if app exists
    if (self::exist($name) === false) {
      throw new App\Exception('Application [' . $name . '] Doesn\'t exist');
    }
    self::initPath($name);
    // Load Conf
    self::_loadConf();
    // Load Routes
    $routes = self::_loadRoutes();
    // load User
    //self::_loadUser();
    // load helpers for the views
    //self::_loadHelpers();
    // load translation
    //self::_loadTranslation();
    // process the route and detect what is the request

    //$request = Ea_Route::run();
    // run Request

    $request = Request::createFromGlobals();

    $context = new RequestContext();
    $context->fromRequest($request);
    $matcher = new UrlMatcher($routes, $context);
    dump($matcher);
    $attributes = $matcher->match($request->getPathInfo());
    dump($attributes);
    self::_runRequest($request, $matcher);
  }
  
//   public static function create($name)
//   {
//     // test if the app exist
//     if (self::exist($name) === true) {
//       throw new Core_App_Exception('Application [' . $name . '] already exist');
//     }
//     try {
//       // create application structure
//       self::_createApplicationStructure($name);
//       // create the web structure
//       self::_createWebStructure($name);
//     }
//     catch (Exception $e) {
//       self::delete($name);
//       throw new Core_App_Exception($e->getMessage());
//     }
//   }
  
//   private static function _createApplicationStructure($name)
//   {
//     try {
//       $path = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $name;
//       $path_assets = dirname(__FILE__) . Ea::DS . 'App' . Ea::DS . 'assets';
//       // create /apps/$name 
//       Ea_Dir::create($path);
//       // create /apps/$name/config
//       Ea_Dir::copy($path_assets . Ea::DS . self::PATH_APP_CONFIG, $path . Ea::DS . self::PATH_APP_CONFIG);
//       // create /apps/$name/libs
//       Ea_Dir::copy($path_assets . Ea::DS . self::PATH_APP_LIBS, $path . Ea::DS . self::PATH_APP_LIBS);
//       // create /apps/$name/modules
//       Ea_Dir::create($path . Ea::DS . self::PATH_APP_MODULES);
//       // create /apps/$name/templates
//       Ea_Dir::copy($path_assets . Ea::DS . self::PATH_APP_TEMPLATES, $path . Ea::DS . self::PATH_APP_TEMPLATES);
//       // create /tmp/$name with write permission
//       Ea_Dir::create(Ea::getPath() . Ea::DS . Ea::PATH_TMP . Ea::DS . $name, 0777);
//       /*
//       // create /tests/apps/$name
//       $path_app_test = Ea::getPath() . Ea::DS . Ea::PATH_TESTS . Ea::DS . 'apps' . Ea::DS . $name;
//       Ea_Dir::create($path_app_test);
//       // create /tests/apps/$name/unit
//       Ea_Dir::create($path_app_test . Ea::DS . 'unit');
//       // create /tests/apps/$name/functionnal
//       Ea_Dir::create($path_app_test . Ea::DS . 'functionnal');
//       */
//     }
//     catch (Exception $e) {
//       $msg = 'Unable to create the application [' . $name . '].';
//       $msg .= ' Please check that you have permissions.' . PHP_EOL;
//       $msg .= '[error]: ' . $e->getMessage();
//       throw new Ea_App_Exception($msg);
//     }
//   }
  
//   private static function _createWebStructure($name)
//   {
//     try {
//       $path = Ea::getPath() . Ea::DS . Ea::PATH_WEB . Ea::DS . $name;
//       $path_assets = dirname(__FILE__) . Ea::DS . 'App' . Ea::DS . 'assets';
//       //create /web/$name
//       Ea_Dir::create($path);
//       //create /web/$name/* from skeleton
//       Ea_Dir::copy($path_assets . Ea::DS . Ea::PATH_WEB, $path);
//       // make it writable for the web
//       if (Ea_Dir::exist($path)) {
//         Ea_Dir::chmod($path . Ea::DS . 'uploads', 0777);
//       }
//     }
//     catch (Exception $e) {
//       $msg = 'Unable to create the application [' . $name . '].';
//       $msg .= ' Please check that you have permissions.' . PHP_EOL;
//       $msg .= '[error]: ' . $e->getMessage();
//       throw new Ea_App_Exception($msg);
//     }
//   }
  
//   public static function createModule($name, $app)
//   {
//     $path_module = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $app . 
//                    Ea::DS . self::PATH_APP_MODULES . Ea::DS . $name;
//     if (Ea_Dir::exist($path_module)) {
//       throw new Ea_App_Exception('Module [' . $name . '] from application [' . $app . '] already exist');
//     }
//     try {
//       self::_createModuleStructure($path_module, $name);
//     }
//     catch (Exception $e) {
//       self::deleteModule($name, $app);
//       throw new Ea_App_Exception($e->getMessage());
//     }
//   }
  
//   private static function _createModuleStructure($path, $name)
//   {
//     $path_assets = dirname(__FILE__) . Ea::DS . 'App' . Ea::DS . 'assets' . Ea::DS . self::PATH_APP_MODULES;
    
//     // create /apps/$app/modules/$name 
//     Ea_Dir::create($path);
//     // create /apps/$app/modules/$name/templates
//     $path_templates = $path . Ea::DS . self::PATH_APP_TEMPLATES;
//     Ea_Dir::create($path_templates);
//     // create file /apps/$app/modules/$name/templates/index.php
//     $content = Ea_File::getContents($path_assets . Ea::DS . 'index.php');
//     $content = str_replace('__CHANGE_ME__', $name, $content);
//     Ea_File::putContents($path_templates . Ea::DS . 'index.php', $content);
//     // create file /apps/$app/modules/$name/actions.php
//     $content = Ea_File::getContents($path_assets . Ea::DS . 'actions.php');
//     $content = str_replace('__CHANGE_ME__', $name, $content);
//     Ea_File::putContents($path . Ea::DS . 'actions.php', $content);
//   }
  
//   public static function deleteModule($name, $app)
//   {
//     $path_module = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $app . 
//                    Ea::DS . self::PATH_APP_MODULES . Ea::DS . $name;
                   
//     if (Ea_Dir::exist($path_module) === false) {
//       throw new Ea_App_Exception('Module [' . $name . '] from application [' . $app . '] doesn\'t exist');
//     }
//     try {
//       Ea_Dir::delete($path_module);
//     }
//     catch (Exception $e) {
//       throw new Ea_App_Exception('Unable to delete the module [' . $name . '] from the application [' . $app . ']');
//     }
//   }
  
//   public static function moduleExist($name, $app)
//   {
//     $path = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $app . 
//             Ea::DS . self::PATH_APP_MODULES . Ea::DS . $name;
//     return Ea_Dir::exist($path);
//   }
  
  public static function exist($name)
  {
    $path = Core::getPath() . Core::DS . Core::PATH_APPS . Core::DS . $name;
    return is_dir($path);
  }
  
//   public static function delete($name)
//   {
//     if (self::exist($name) === false) {
//       throw new Ea_App_Exception('Application [' . $name . '] does not exist');
//     }
//     try {
//       $path_app  = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $name;
//       $path_tmp  = Ea::getPath() . Ea::DS . Ea::PATH_TMP . Ea::DS . $name;
//       $path_test = Ea::getPath() . Ea::DS . Ea::PATH_TESTS . Ea::DS . 'apps' . Ea::DS . $name;
//       $path_web  = Ea::getPath() . Ea::DS . Ea::PATH_WEB . Ea::DS . $name;
//       // delete application dirs
//       Ea_Dir::delete($path_app);
//       // delete tmp dir
//       Ea_Dir::delete($path_tmp);
//       // delete tests dir
//       Ea_Dir::delete($path_test);
//       // delete web files
//       Ea_Dir::delete($path_web);
//     }
//     catch (Exception $e) {
//       throw new Ea_App_Exception('Unable to delete the application [' . $name . ']');
//     }
//   }
  
//   public static function generateTranslation($name, $culture = 'default')
//   {
//     if (self::exist($name) === false) {
//       throw new Ea_App_Exception('Application [' . $name . '] does not exist');
//     }
//     $culture = strtolower($culture);
//     $path = Ea::getPath() . Ea::DS . Ea::PATH_APPS . Ea::DS . $name;
//     $path_assets = dirname(__FILE__) . Ea::DS . 'App' . Ea::DS . 'assets';
//     $a_file = self::PATH_APP_LOCALE . Ea::DS . 'default.php';
//     $t_file = self::PATH_APP_LOCALE . Ea::DS . $culture . '.php';
//     // if not exist, create it.
//     if (!Ea_Dir::exist($path . Ea::DS . self::PATH_APP_LOCALE)) {
//       Ea_Dir::create($path . Ea::DS . self::PATH_APP_LOCALE);
//     }
//     if (!Ea_File::exist($path . Ea::DS . $t_file)) {
//       Ea_File::copy($path_assets . Ea::DS . $a_file, $path . Ea::DS . $t_file);
//     }
//     // load the translation file
//     require_once($path . Ea::DS . $t_file);
    
//     // get all the php files of the app
//     $files = Ea_File::glob('*.php', 0, $path);
//     $new_t = '<?php ' . PHP_EOL . PHP_EOL;
//     $new_t .= '/** ' . PHP_EOL;
//     $new_t .= ' * ' . PHP_EOL;
//     $new_t .= ' * Automatically Generated Translation File' . PHP_EOL;
//     $new_t .= ' * Date: ' . date('Y-m-d H:i:s') . PHP_EOL;
//     $new_t .= ' * ' . PHP_EOL;
//     $new_t .= ' */' . PHP_EOL . PHP_EOL;
//     $new_t .= 'global $__t;' . PHP_EOL . PHP_EOL;
//     $new_t .= '/* START EDITING FROM HERE */' . PHP_EOL . PHP_EOL;
//     $used = $matches = $all = array();    
//     foreach ($files as $file) {
//       $tmp = Ea_File::getContents($file);
//       $tokens = token_get_all($tmp);
//       //Ea::d($tokens);
//       $start = 0;
//       foreach ($tokens as $key => $token) {
//         if (is_array($token)) {
//           if ($token[0] == 310 && $token[1] == 't') {
//             $start = 1;
//             $tmp1 = substr($tokens[$key+2][1], 1, -1);
//             if (!isset($all[$tmp1])) {
//               $all[$tmp1] = $tmp1;
//               $matches[$file][] = $tmp1;
//             }
//           }
//         }
//         elseif ($start == 1 && $token == ';') {
//           $start = 0;
//         }
//       }
//     }
//     if (count($matches) > 1) {
//       foreach ($matches as $file => $filematches) {
//         $new_t .= '/* ' . $file . ' */' . PHP_EOL . PHP_EOL;
//         foreach ($filematches as $match) {
//           if (!isset($used[stripslashes($match)])) {
//             if (isset($__t[stripslashes($match)])) {
//               $new_t .= '$__t[\'' . $match . '\'] = \'' . addcslashes($__t[stripslashes($match)], '\'\\') . '\';' . PHP_EOL;
//               unset($__t[stripslashes($match)]);
//             }
//             else {
//               $new_t .= '$__t[\'' . $match . '\'] = \'TODO\';' . PHP_EOL;
//             }
//             $new_t .= PHP_EOL;
//             $used[stripslashes($match)] = true;
//           }
//         }
//       }
//     }
//     if (Ea_File::exist($path . Ea::DS . $t_file)) {
//       Ea_File::copy($path . Ea::DS . $t_file, $path . Ea::DS . $t_file . '.bak');
//     }
//     Ea_File::putContents($path . Ea::DS . $t_file, $new_t);
//   }

//   public static function deployAssets($name, $version)
//   {
//     if (self::exist($name) === false) {
//       throw new Ea_App_Exception('Application [' . $name . '] Doesn\'t exist');
//     }    
//     self::initPath($name);
//     self::_loadConf();

//     // unlink all ~ files
    
//     $dirs = Ea_Config::get('assets.folders');
//     $tmp_path = Ea::getPath() . Ea::DS . Ea::PATH_WEB . Ea::DS . $name . '/';
//     $files = Ea_File::glob('*~', false, $tmp_path);
//     foreach ($files as $file) {
//       Ea_File::delete($file);
//     }
//     $options = array(
//                       'before' => function(\AWS\Command $command) {
//                                     $command['ACL'] = 'public-read';
//                                   }
//                     );
//     if ($dirs) {
//       foreach ($dirs as $dir) {
//         echo 'Deploying ' . str_pad($tmp_path . $dir, 80, ' ');
//         Ea_S3::uploadDirectory($tmp_path . $dir, Ea_Config::get('amazon.s3.assets.bucket'), $dir . '/', $options);
//         echo '[DONE]' . "\n";
//       }
//     }
//   }
}
