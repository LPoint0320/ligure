<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

// Theme constants (used for cache keys, etc.)
if (!defined('LIGURE_THEME_VERSION')) {
    define('LIGURE_THEME_VERSION', '1.0.0');
}

// ==================== 涓诲叆鍙ｆ枃浠?====================
// Typecho鏍囧噯缁撴瀯锛歠unctions/ 鐩綍瀛樻斁鍔熻兘鏂囦欢

// 缂撳瓨灞傦細鏂囦欢缂撳瓨绛?
require_once __DIR__ . '/functions/cache.php';

// 鏂囩珷椤靛姛鑳斤細TOC銆佸浘鐗囥€佽〃鎯呫€佹枃绔犲瓧娈?
require_once __DIR__ . '/functions/article.php';

// 棣栭〉鍔熻兘锛氬垪琛ㄣ€佸崱鐗囩瓑

// 渚ц竟鏍忓姛鑳斤細缁熻銆佹悳绱€佸垎绫汇€佹爣绛?
require_once __DIR__ . '/functions/sidebar.php';

// 鐭唬鐮佸姛鑳斤細瑙ｆ瀽涓庢覆鏌?
require_once __DIR__ . '/functions/shortcodes.php';

// 閫氱敤鍔熻兘锛氬垵濮嬪寲銆侀厤缃€丆DN銆侀厤鑹?
require_once __DIR__ . '/functions/common.php';

// 娓叉煋绠￠亾锛氬崗璋冨悇妯″潡澶勭悊鍐呭
require_once __DIR__ . '/functions/render.php';

// OWO 琛ㄦ儏闈㈡澘锛氭湇鍔＄娓叉煋
require_once __DIR__ . '/functions/owo.php';

