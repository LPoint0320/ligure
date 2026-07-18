<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

// ==================== 娓叉煋绠￠亾妯″潡 ====================
// 鍗忚皟鍚勯〉闈㈡ā鍧楋紝鎸夐『搴忓鐞嗘枃绔犲唴瀹?
function psGetRenderOptionFingerprint()
{
    $options = Typecho_Widget::widget('Widget_Options');

    // 鍙寘鍚湡姝ｅ奖鍝嶆覆鏌撹緭鍑虹殑閰嶇疆
    $fingerprint = [
        'v' => defined('LIGURE_THEME_VERSION') ? LIGURE_THEME_VERSION : '0',
        'toc' => (string)($options->showTOC ?? '1')
    ];

    return md5(json_encode($fingerprint));
}

function psRenderContentPipeline($content)
{
    $steps = [
        'parseShortcodes',
        'parseAlerts',
        'parseWindows',
        'parseTimeline',
        'parsePicGrid',
        'wrapTables',
        'addZoomableToImages',
        'parseOwOcodes',
        'generateToc'
    ];

    foreach ($steps as $step) {
        if (!function_exists($step)) {
            continue;
        }
        $content = $step($content);
    }

    $content = psNormalizeBlockParagraphs($content);
    $content = psCleanupEmptyParagraphs($content);

    return $content;
}

// 淇鐭唬鐮佽В鏋愬悗浜х敓鐨?<p><div...></div></p> 缁撴瀯锛岄伩鍏嶆祻瑙堝櫒鑷姩鎻掑叆绌烘钀姐€?function psNormalizeBlockParagraphs($content)
{
    if (!is_string($content) || $content === '') {
        return (string)$content;
    }

    $blockTags = '(?:div|section|article|aside|figure|ul|ol|table|blockquote|pre|h[1-6])';
    $spaceLike = '(?:\s|&nbsp;|&#160;|<br\s*\/?>)*';

    // <p> + 绌虹櫧 + 鍧楃骇寮€濮嬫爣绛?=> 鍘绘帀寮€澶?p
    $content = preg_replace('/<p>' . $spaceLike . '(<'.$blockTags.'\b[^>]*>)/iu', '$1', $content);
    // 鍧楃骇缁撴潫鏍囩 + 绌虹櫧 + </p> => 鍘绘帀缁撳熬 p
    $content = preg_replace('/(<\/'.$blockTags.'>)' . $spaceLike . '<\/p>/iu', '$1', $content);

    return $content;
}

// 娓呯悊瑙ｆ瀽鍚庨仐鐣欑殑绌烘钀斤紝閬垮厤缁勪欢鍓嶅悗鍑虹幇澶氫綑绌虹櫧銆?function psCleanupEmptyParagraphs($content)
{
    if (!is_string($content) || $content === '') {
        return (string)$content;
    }

    return preg_replace('/<p>(?:\s|&nbsp;|&#160;|<br\s*\/?>)*<\/p>/iu', '', $content);
}

// 鍐呭娓叉煋涓诲嚱鏁?function renderPostContent($content)
{
    $source = (string)$content;
    if (trim($source) === '') {
        $GLOBALS['toc_html'] = '';
        return $source;
    }

    $version = defined('LIGURE_THEME_VERSION') ? LIGURE_THEME_VERSION : '0';
    $optionFingerprint = psGetRenderOptionFingerprint();
    $contentHash = md5($source);
    $cacheKey = 'render_post_content:v5:' . $version . ':' . $contentHash . ':' . $optionFingerprint;
    $ttl = 6 * 3600;

    $cache = getCache($cacheKey, $ttl, 'render');
    if (
        $cache &&
        !empty($cache['fresh']) &&
        isset($cache['data']) &&
        is_array($cache['data']) &&
        array_key_exists('content', $cache['data'])
    ) {
        $GLOBALS['toc_html'] = (string)($cache['data']['toc'] ?? '');
        return (string)$cache['data']['content'];
    }

    $content = psRenderContentPipeline($source);
    $toc = (string)($GLOBALS['toc_html'] ?? '');
    setCache($cacheKey, ['content' => $content, 'toc' => $toc], 'render');

    return $content;
}

