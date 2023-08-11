<?

require_once 'config.php';
define('C_REST_CLIENT_ID', $config['bitrix24']['app_id']);//Application ID
define('C_REST_CLIENT_SECRET', $config['bitrix24']['app_secret']);//Application key

// or
//define('C_REST_WEB_HOOK_URL','https://b24-tg4bgg.bitrix24.ru/rest/1/ekqpiysk949hjtha/');//url on creat Webhook

//define('C_REST_CURRENT_ENCODING','windows-1251');
//define('C_REST_IGNORE_SSL',true);//turn off validate ssl by curl
//define('C_REST_LOG_TYPE_DUMP',true); //logs save var_export for viewing convenience
//define('C_REST_BLOCK_LOG',true);//turn off default logs
//define('C_REST_LOGS_DIR', __DIR__ .'/logs/'); //directory path to save the log