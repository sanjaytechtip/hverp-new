<?php
// define('PUBLIC_FOLDER', 'public/'); // local
// define('ANTYA_API_URL','http://192.168.0.16/acerp/');

// //define('PUBLIC_FOLDER', '/'); // live

// define('API_KEY','bf45c093e542f057c123ae568');

// define('BG_YELLOW', '#f2f766');
// define('BG_GREEN', '#bbeebf');
// define('BG_RED', '#f08f8f');
// define('FAB_CUT_ID', '999');
// define('ANTYA_CLIENTID', 9);
// define('D_CLASS_CLIENT_GROUP_ID',7); 
// define('DISPATCH_ASSISTANT_ORDER_TYPES',  serialize(array(1,3,4,5)));

	define('PUBLIC_FOLDER', 'public/');
	define('ANTYA_API_URL','');
	define('API_KEY','bf45c093e542f057c123ae568');
	define('BG_YELLOW', '#f2f766');
	define('BG_GREEN', '#bbeebf');
	define('BG_RED', '#f08f8f');
	//define('WEBSITE_NAME', 'Pashupati Overseas LLP');
	define('WEBSITE_NAME', 'H.V.TECHNOLOGIES');
	//define('SALES_AGENT', 'agent_-_bangalore,agent_-_ludhiana,agent_-_mumbai');
	define('SALES_AGENT', 'sales_executive,sales_agent,management_sales_executive');
	
	return [
    'options' => [
        'pagination' => '20',
		 'pagination_dashboard' => '5',
		 'currency' => '$'
    ]
];

?>