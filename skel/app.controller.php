<?php
/**
 *    {$project_id}_Controller.php
 *
 *    @author        {$author}
 *    @package    {$project_id}
 *    @version    $Id$
 */

/** アプリケーションベースディレクトリ */
define('BASE', dirname(dirname(__FILE__)));

// include_pathの設定(アプリケーションディレクトリを追加)
$app = BASE . "/app";
$lib = BASE . "/lib";
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . implode(PATH_SEPARATOR, array($app, $lib)));


/** アプリケーションライブラリのインクルード */
include_once('Ethna/Ethna.php');
include_once('{$project_id}_Error.php');

/**
 *    {$project_id}アプリケーションのコントローラ定義
 *
 *    @author        {$author}
 *    @access        public
 *    @package    {$project_id}
 */
class {$project_id}_Controller extends Ethna_Controller
{
    /**#@+
     *    @access    private
     */

    /**
     *    @var    string    アプリケーションID
     */
    protected    $appid = '{$application_id}';

    /**
     *    @var    array    forward定義
     */
    protected $forward = array(
        /*
         *    TODO: ここにforward先を記述してください
         *
         *    記述例：
         *
         *    'index'            => array(
         *        'view_name'    => '{$project_id}_View_Index',
         *    ),
         */
    );

    /**
     *    @var    array    action定義
     */
    protected $action = array(
        /*
         *    TODO: ここにaction定義を記述してください
         *
         *    記述例：
         *
         *    'index'        => array(),
         */
    );

    /**
     *    @var    array    soap action定義
     */
    protected $soap_action = array(
        /*
         *    TODO: ここにSOAPアプリケーション用のaction定義を
         *    記述してください
         *    記述例：
         *
         *    'sample'            => array(),
         */
    );

    /**
     *    @var    array        アプリケーションディレクトリ
     */
    protected $directory = array(
        'action'        => 'app/action',
        'action_cli'    => 'app/action_cli',
        'action_xmlrpc' => 'app/action_xmlrpc',
        'app'           => 'app',
        'bin'           => 'bin',
        'etc'            => 'etc',
        'filter'        => 'app/filter',
        'locale'        => 'locale',
        'log'            => 'log',
        'plugins'        => array(),
        'template'        => 'template',
        'template_c'    => 'tmp',
        'tmp'            => 'tmp',
        'view'            => 'app/view',
    );

    /**
     *    @var    array        DBアクセス定義
     */
    protected    $db = array(
        ''                => DB_TYPE_RW,
    );

    /**
     *    @var    array        拡張子設定
     */
    protected $ext = array(
        'php'            => 'php',
        'tpl'            => 'tpl',
    );

    /**
     *    @var    array    クラス定義
     */
    protected $class = array(
        /*
         *    TODO: 設定クラス、ログクラス、SQLクラスをオーバーライド
         *    した場合は下記のクラス名を忘れずに変更してください
         */
        'class'            => 'Ethna_ClassFactory',
        'backend'        => 'Ethna_Backend',
        'config'        => 'Ethna_Config',
        'db'            => 'Ethna_DB_PEAR',
        'error'            => 'Ethna_ActionError',
        'form'            => 'Ethna_ActionForm',
        'i18n'            => 'Ethna_I18N',
        'logger'        => 'Ethna_Logger',
        'session'        => 'Ethna_Session',
        'sql'            => 'Ethna_AppSQL',
        'view'            => 'Ethna_ViewClass',
    );

    /**
     *    @var    array        フィルタ設定
     */
    protected $filter = array(
        /*
         *    TODO: フィルタを利用する場合はここにそのクラス名を
         *    記述してください
         *
         *    記述例：
         *
         *    '{$project_id}_Filter_ExecutionTime',
         */
    );

    /**
     *    @var    array    マネージャ一覧
     */
    protected $manager = array(
        /*
         *    TODO: ここにアプリケーションのマネージャオブジェクト一覧を
         *    記述してください
         *
         *    記述例：
         *
         *    'um'    => 'User',
         */
    );

    /**
     *    @var    array    smarty modifier定義
     */
    protected $smarty_modifier_plugin = array(
        /*
         *    TODO: ここにユーザ定義のsmarty modifier一覧を記述してください
         *
         *    記述例：
         *
         *    'smarty_modifier_foo_bar',
         */
    );

    /**
     *    @var    array    smarty function定義
     */
    protected $smarty_function_plugin = array(
        /*
         *    TODO: ここにユーザ定義のsmarty function一覧を記述してください
         *
         *    記述例：
         *
         *    'smarty_function_foo_bar',
         */
    );

    /**
     *    @var    array    smarty prefilter定義
     */
    protected $smarty_prefilter_plugin = array(
        /*
         *    TODO: ここにユーザ定義のsmarty prefilter一覧を記述してください
         *
         *    記述例：
         *
         *    'smarty_prefilter_foo_bar',
         */
    );

    /**
     *    @var    array    smarty postfilter定義
     */
    protected $smarty_postfilter_plugin = array(
        /*
         *    TODO: ここにユーザ定義のsmarty postfilter一覧を記述してください
         *
         *    記述例：
         *
         *    'smarty_postfilter_foo_bar',
         */
    );

    /**
     *    @var    array    smarty outputfilter定義
     */
    protected $smarty_outputfilter_plugin = array(
        /*
         *    TODO: ここにユーザ定義のsmarty outputfilter一覧を記述してください
         *
         *    記述例：
         *
         *    'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

    /**
     *    遷移時のデフォルトマクロを設定する
     *
     *    @access    protected
     *    @param    object    Smarty    $smarty    テンプレートエンジンオブジェクト
     */
    function _setDefaultTemplateEngine(&$smarty)
    {
        /*
         *    TODO: ここでテンプレートエンジンの初期設定や
         *  全てのビューに共通なテンプレート変数を設定します
         *
         *    記述例：
         * $smarty->assign('session_name', session_name());
         * $smarty->assign('session_id', session_id());
         *
         * // ログインフラグ(true/false)
         * $session = $this->getClassFactory('session');
         * if ($session && $this->session->isStart()) {
         *     $smarty->assign('login', $session->isStart());
         * }
         */
    }
}

