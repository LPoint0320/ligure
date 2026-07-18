<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

// ==================== 閫氱敤鍔熻兘妯″潡 ====================
// 鍖呭惈锛氫富棰樺垵濮嬪寲銆侀厤缃鐞嗐€丆DN銆侀厤鑹层€佺増鏈鏌?
// 涓婚鍒濆鍖栭挬瀛?function themeInit($archive)
{
    Helper::options()->commentsAntiSpam = false;

    // 淇鍔犲瘑鏂囩珷 PJAX 鍏煎鎬?    if ($archive->is('single') && $archive->hidden && $archive->request->isAjax()) {
        $archive->response->setStatus(200);
    }

    // AJAX 鎺ュ彛锛氳幏鍙?Token URL
    if ($archive->is('post') && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'getTokenUrl') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['tokenUrl' => Typecho_Widget::widget('Widget_Security')->getTokenUrl($archive->permalink)]);
        exit;
    }

    // AJAX 鎺ュ彛锛氭鏌ユ枃绔犳槸鍚︿粛涓哄姞瀵嗙姸鎬?    if ($archive->is('post') && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'checkPassword') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['hidden' => $archive->hidden]);
        exit;
    }
}

// 鑾峰彇閰嶈壊鏂规
function getColorScheme()
{
    $colorScheme = Typecho_Widget::widget('Widget_Options')->colorScheme;
    return $colorScheme;
}

// 闈欐€佽祫婧?URL 绠＄悊
function resolveStaticURL($path)
{
    $options = Typecho_Widget::widget('Widget_Options');
    $staticCdn = $options->staticCdn;

    $staticMap = [
        'local' => [
            'medium-zoom.min.js' => $options->themeUrl . '/js/lib/medium-zoom.min.js',
            'Swup.umd.min.js' => $options->themeUrl . '/js/lib/Swup/Swup.umd.min.js',
        ],
        'bootcdn' => [
            'medium-zoom.min.js' => "https://cdn.bootcdn.net/ajax/libs/medium-zoom/1.1.0/medium-zoom.min.js",
            'Swup.umd.min.js' => "https://cdn.bootcdn.net/ajax/libs/swup/4.8.2/Swup.umd.min.js",
        ],
        "cdnjs" => [
            'medium-zoom.min.js' => "https://cdnjs.cloudflare.com/ajax/libs/medium-zoom/1.1.0/medium-zoom.min.js",
            'Swup.umd.min.js' => "https://cdnjs.cloudflare.com/ajax/libs/swup/4.8.2/Swup.umd.min.js",
        ]
    ];

    if ($staticCdn === 'local') {
        return $staticMap['local'][$path];
    }

    if (isset($staticMap[$staticCdn][$path])) {
        return $staticMap[$staticCdn][$path];
    }

    return $staticMap['local'][$path];
}

function getStaticURL($path)
{
    echo resolveStaticURL($path);
}

// 鐢熸垚鍔ㄦ€?CSS
function generateDynamicCSS()
{
    $colorScheme = getColorScheme();

    $colorMap = [
        'pink' => ['theme' => '#ea868f', 'hover' => '#d1606e'],
        'green' => ['theme' => '#5fae8a', 'hover' => '#3f8f6a'],
        'blue' => ['theme' => '#6482db', 'hover' => '#4f6fdc'],
        'yellow' => ['theme' => '#e5b96e', 'hover' => '#cfa24d'],
        'red' => ['theme' => '#cd575f', 'hover' => '#b84a4a'],
        'purple' => ['theme' => '#8f7acb', 'hover' => '#6d5fb3'],
        'cyan' => ['theme' => '#5fb3b8', 'hover' => '#3f8f93'],
        'orange' => ['theme' => '#e39a5c', 'hover' => '#c97a3f'],
    ];

    $defaultColor = ['theme' => '#ea868f', 'hover' => '#d1606e'];
    $colors = isset($colorMap[$colorScheme]) ? $colorMap[$colorScheme] : $defaultColor;
    $themeColor = $colors['theme'];
    $themeHoverColor = $colors['hover'];

    $darkColorMap = [
        'pink' => ['theme' => '#bf677a', 'hover' => '#d6728a'],
        'green' => ['theme' => '#3f8a6c', 'hover' => '#2f6f56'],
        'blue' => ['theme' => '#44579a', 'hover' => '#5b6fc4'],
        'yellow' => ['theme' => '#ab8748', 'hover' => '#cfa24d'],
        'red' => ['theme' => '#9a444b', 'hover' => '#b84a4a'],
        'purple' => ['theme' => '#5f548a', 'hover' => '#7668a8'],
        'cyan' => ['theme' => '#3f7a7f', 'hover' => '#5f9ea3'],
        'orange' => ['theme' => '#9f5a2f', 'hover' => '#b86a3a'],
    ];

    $darkColors = isset($darkColorMap[$colorScheme]) ? $darkColorMap[$colorScheme] : $defaultColor;
    $darkThemeColor = $darkColors['theme'];
    $darkThemeHoverColor = $darkColors['hover'];

    echo '<style>
        :root {
            --themecolor: ' . htmlspecialchars($themeColor, ENT_QUOTES, 'UTF-8') . ';
            --themehovercolor: ' . htmlspecialchars($themeHoverColor, ENT_QUOTES, 'UTF-8') . ';
        }

        [data-theme="dark"] {
            --themecolor: ' . htmlspecialchars($darkThemeColor, ENT_QUOTES, 'UTF-8') . ';
            --themehovercolor: ' . htmlspecialchars($darkThemeHoverColor, ENT_QUOTES, 'UTF-8') . ';
        }
    </style>';
}

// 璇诲彇涓婚甯冨皵閰嶇疆椤癸紙鍏煎鏃у€硷級
function psOptionEnabled($value, $default = true)
{
    if ($value === null) {
        return (bool)$default;
    }

    if (is_bool($value)) {
        return $value;
    }

    $normalized = strtolower(trim((string)$value));
    if ($normalized === '1' || $normalized === 'true' || $normalized === 'on' || $normalized === 'yes') {
        return true;
    }

    if ($normalized === '0' || $normalized === 'false' || $normalized === 'off' || $normalized === 'no') {
        return false;
    }

    return (bool)$default;
}

function getPSRuntimeConfig($archive = null)
{
    $options = Typecho_Widget::widget('Widget_Options');

    $features = [
        'showTOC' => psOptionEnabled($options->showTOC ?? '1', true)
    ];

    $pageType = 'list';
    if ($archive && method_exists($archive, 'is')) {
        if ($archive->is('post')) {
            $pageType = 'post';
        } elseif ($archive->is('page')) {
            $pageType = 'page';
        }
    }

    return [
        'themeVersion' => defined('LIGURE_THEME_VERSION') ? LIGURE_THEME_VERSION : '0',
        'themeUrl' => $options->themeUrl,
        'siteUrl' => $options->siteUrl,
        'pageType' => $pageType,
        'features' => $features,
        'assets' => [
            'mediumZoom' => resolveStaticURL('medium-zoom.min.js'),
            'owo' => $options->themeUrl . '/js/OwO.min.js'
        ]
    ];
}

function outputPSRuntimeConfigScript($archive = null)
{
    $config = getPSRuntimeConfig($archive);
    $json = json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        $json = '{}';
    }

    echo '<script>window.PS_CONFIG = ' . $json . ';</script>';
}

// 鑾峰彇 GitHub 鏈€鏂扮増鏈彿
function getLatestGitHubRelease($owner, $repo)
{
    $owner = trim((string)$owner);
    $repo = trim((string)$repo);
    if ($owner === '' || $repo === '') {
        return false;
    }

    $cacheKey = 'release:v1:' . strtolower($owner) . '/' . strtolower($repo);
    $ttl = 6 * 3600;
    $cache = getGithubCache($cacheKey, $ttl);
    if ($cache && !empty($cache['fresh'])) {
        return $cache['data'];
    }

    $url = "https://api.github.com/repos/{$owner}/{$repo}/releases/latest";
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: PureSuck-Theme\r\n"
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        if ($cache && isset($cache['data'])) {
            return $cache['data'];
        }
        return false;
    }

    $data = json_decode($response, true);

    if ($data && isset($data['tag_name'])) {
        $version = ltrim($data['tag_name'], 'v');
        setGithubCache($cacheKey, $version);
        return $version;
    }

    if ($cache && isset($cache['data'])) {
        return $cache['data'];
    }

    return false;
}

// 涓婚閰嶇疆琛ㄥ崟
function themeConfig($form)
{
    # 涓婚淇℃伅鍙婂姛鑳?    $str1 = explode('/themes/', Helper::options()->themeUrl);
    $str2 = explode('/', $str1[1]);
    $name = $str2[0];
    $db = Typecho_Db::get();
    $sjdq = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name));
    $ysj = $sjdq['value'];

    if (isset($_POST['type'])) {
        if ($_POST["type"] == "澶囦唤妯℃澘璁剧疆鏁版嵁") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                $update = $db->update('table.options')->rows(array('value' => $ysj))->where('name = ?', 'theme:' . $name . 'bf');
                $db->query($update);
                echo '<div class="tongzhi home">澶囦唤宸叉洿鏂帮紝璇风瓑寰呰嚜鍔ㄥ埛鏂帮紒濡傛灉绛変笉鍒拌鐐瑰嚮';
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">杩欓噷</a></div>
                <script language="JavaScript">
                    window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
                </script>
                <?php
            } else {
                if ($ysj) {
                    $insert = $db->insert('table.options')
                        ->rows(array('name' => 'theme:' . $name . 'bf', 'user' => '0', 'value' => $ysj));
                    $db->query($insert);
                    echo '<div class="tongzhi home">澶囦唤瀹屾垚锛岃绛夊緟鑷姩鍒锋柊锛佸鏋滅瓑涓嶅埌璇风偣鍑?;
                    ?>
                    <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">杩欓噷</a></div>
                    <script language="JavaScript">
                        window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
                    </script>
                    <?php
                }
            }
        }
        if ($_POST["type"] == "杩樺師妯℃澘璁剧疆鏁版嵁") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                $sjdub = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'));
                $bsj = $sjdub['value'];
                $update = $db->update('table.options')->rows(array('value' => $bsj))->where('name = ?', 'theme:' . $name);
                $db->query($update);
                echo '<div class="tongzhi home">妫€娴嬪埌妯℃澘澶囦唤鏁版嵁锛屾仮澶嶅畬鎴愶紝璇风瓑寰呰嚜鍔ㄥ埛鏂帮紒濡傛灉绛変笉鍒拌鐐瑰嚮';
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">杩欓噷</a></div>
                <script language="JavaScript">
                    window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2000);
                </script>
                <?php
            } else {
                echo '<div class="tongzhi home">娌℃湁妯℃澘澶囦唤鏁版嵁锛屾仮澶嶄笉浜嗗摝锛?/div>';
            }
        }
        if ($_POST["type"] == "鍒犻櫎澶囦唤鏁版嵁") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                $delete = $db->delete('table.options')->where('name = ?', 'theme:' . $name . 'bf');
                $db->query($delete);
                echo '<div class="tongzhi home">鍒犻櫎鎴愬姛锛岃绛夊緟鑷姩鍒锋柊锛屽鏋滅瓑涓嶅埌璇风偣鍑?;
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">杩欓噷</a></div>
                <script language="JavaScript">
                    window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
                </script>
                <?php
            } else {
                echo '<div class="tongzhi home">涓嶇敤鍒犱簡锛佸浠戒笉瀛樺湪锛侊紒锛?/div>';
            }
        }
    }

    $themeOptionsUrl = '';
    ob_start();
    $themeOptionsUrlCandidate = Helper::options()->adminUrl('options-theme.php');
    $themeOptionsUrlBuffered = trim((string)ob_get_clean());
    if (is_string($themeOptionsUrlCandidate) && $themeOptionsUrlCandidate !== '') {
        $themeOptionsUrl = $themeOptionsUrlCandidate;
    } elseif ($themeOptionsUrlBuffered !== '') {
        $themeOptionsUrl = $themeOptionsUrlBuffered;
    }
    if ($themeOptionsUrl === '') {
        $themeOptionsUrl = 'options-theme.php';
    }
    $themeOptionsUrl = htmlspecialchars($themeOptionsUrl, ENT_QUOTES, 'UTF-8');

    echo '
    <style id="ps-theme-config-lite">
        .ps-theme-config-lite {
            margin: 12px 0 16px;
            padding: 12px 14px;
            border: 1px solid #efeaeb;
            border-left: 4px solid #ea868f;
            border-radius: 6px;
            background: #fff;
        }

        .ps-theme-config-lite h2 {
            margin: 0 0 6px;
            font-size: 16px;
            color: #333;
        }

        .ps-theme-config-lite p {
            margin: 0;
            color: #666;
            line-height: 1.7;
        }

        .ps-theme-config-lite .ps-theme-config-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ps-theme-meta-lite {
            margin: 10px 0 12px;
            line-height: 1.7;
            color: #666;
        }

        .ps-theme-meta-lite .ps-current-version {
            color: #b45864;
            font-weight: 600;
        }

        .ps-theme-meta-lite .ps-version-new {
            color: #d95f5f;
        }

        .ps-theme-meta-lite .ps-version-latest {
            color: #4ca36c;
        }

        .ps-theme-backup-lite {
            margin: 8px 0 14px;
            padding: 10px 12px;
            border: 1px solid #efefef;
            border-radius: 6px;
            background: #fff;
        }

        .ps-theme-backup-lite p {
            margin: 0 0 8px;
            color: #666;
        }
    </style>
    <div class="ps-theme-config-lite">
        <h2>PureSuck 涓婚閰嶇疆</h2>
        <p>骞插噣锛岀函娲侊紝娣￠泤鏈寸礌鐨?Typecho 涓婚</p>
        <p>甯屾湜杩欎唤骞插噣鐨勭埍鎭嬭兘寰楀埌浣犵殑鍠滄</p>
        <div class="ps-theme-config-actions">
            <a class="btn btn-s" href="https://www.moxiify.cn" target="_blank" rel="noopener noreferrer">浣滆€呬富椤?/a>
            <a class="btn btn-s" href="https://github.com/MoXiaoXi233/PureSuck-theme" target="_blank" rel="noopener noreferrer">涓婚鏂囨。</a>
        </div>
    </div>';

    // 鑾峰彇鏈€鏂扮増鏈彿
    $currentVersion = defined('LIGURE_THEME_VERSION') ? LIGURE_THEME_VERSION : '1.3.2';
    $latestVersion = getLatestGitHubRelease('MoXiaoXi233', 'PureSuck-theme');
    $versionHtml = '<div class="ps-theme-meta-lite">褰撳墠涓婚鐗堟湰锛?span class="ps-current-version">' . htmlspecialchars($currentVersion) . '</span>';

    if ($latestVersion) {
        if (version_compare($latestVersion, $currentVersion, '>')) {
            $versionHtml .= ' <span class="ps-version-new">(鏈夋柊鐗堟湰: ' . htmlspecialchars($latestVersion) . ')</span>';
        } else {
            $versionHtml .= ' <span class="ps-version-latest">(宸叉槸鏈€鏂?</span>';
        }
    }

    $versionHtml .= '</div>';

    echo $versionHtml . '
    <div class="ps-theme-backup-lite">
    <p>涓婚寮€婧愰〉闈㈠強鏂囨。锛?a href="https://github.com/MoXiaoXi233/PureSuck-theme" target="_blank" rel="noopener noreferrer">PureSuck-theme</a></p>
    <p>*澶囦唤鍔熻兘鍙湪 SQL 鐜涓嬫祴璇曟甯革紝閬囧埌闂璇锋竻绌洪厤缃噸鏂板～鍐?</p>
    <form class="protected home" action="?' . $name . 'bf" method="post">
    <input type="submit" name="type" class="btn btn-s" value="澶囦唤妯℃澘璁剧疆鏁版嵁" />  <input type="submit" name="type" class="btn btn-s" value="杩樺師妯℃澘璁剧疆鏁版嵁" />  <input type="submit" name="type" class="btn btn-s" value="鍒犻櫎澶囦唤鏁版嵁" /></form>
    </div>';

    // 缃戦〉 icon URL 閰嶇疆椤?    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('favicon.ico 鍦板潃'),
        _t('濉啓ico鏍煎紡鍥剧墖 URL 鍦板潃, 鍦ㄧ綉绔欐爣棰樺墠鍔犱笂涓€涓浘鏍?)
    );
    $form->addInput($logoUrl);

    // 缃戠珯鏍囬閰嶇疆椤?    $titleIndex = new \Typecho\Widget\Helper\Form\Element\Text(
        'titleIndex',
        null,
        null,
        _t('缃戠珯鏍囬'),
        _t('缃戠珯宸︿晶鏍囬鏂囧瓧')
    );
    $form->addInput($titleIndex);

    // 宸︿晶 LOGO URL 閰嶇疆椤?    $logoIndex = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoIndex',
        null,
        null,
        _t('宸︿晶 LOGO 鍦板潃'),
        _t('濉啓 JPG/PNG/Webp 绛夊浘鐗?URL 鍦板潃, 缃戠珯宸︿晶澶村儚鐨勬樉绀?(512*512鏈€浣? ')
    );
    $form->addInput($logoIndex);

    // 宸︿晶 Logo 璺宠浆閾炬帴 閰嶇疆椤?    $logoIndexUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoIndexUrl',
        null,
        null,
        _t('LOGO 璺宠浆 鍦板潃'),
        _t('鐐瑰嚮澶村儚鏃跺€欒烦杞殑缃戝潃锛屽彲浠ヨ缃负寮曞椤电瓑锛屼负绌哄垯涓哄崥瀹㈤椤?)
    );
    $form->addInput($logoIndexUrl);

    //浣滆€呭ご鍍?    $authorAvatar = new \Typecho\Widget\Helper\Form\Element\Text(
        'authorAvatar',
        null,
        null,
        _t('浣滆€呭ご鍍忓湴鍧€'),
        _t('濉啓 JPG/PNG/Webp 绛夊浘鐗?URL 鍦板潃, 鐢ㄤ簬鏄剧ず鏂囩珷浣滆€呭ご鍍?)
    );
    $form->addInput($authorAvatar);

    // 宸︿晶鎻忚堪
    $customDescription = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customDescription',
        null,
        null,
        _t('宸︿晶涓汉鎻忚堪'),
        _t('濉啓鑷畾涔夋弿杩板唴瀹癸紝鏈€濂界畝鐭紝灏嗗湪缃戠珯宸︿晶绔欏悕涓嬫樉绀?)
    );
    $form->addInput($customDescription);

    // 宸︿晶鑷畾涔?    $leftSideCustomCode = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'leftSideCustomCode',
        null,
        null,
        _t('宸︿晶鑷畾涔夊尯鍩?),
        _t('鏀寔鑷畾涔塇TML锛屽皢鍦ㄧ綉绔欏乏渚ф樉绀?)
    );
    $form->addInput($leftSideCustomCode);

    // Footer Script鏍囩
    $footerScript = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'footerScript',
        null,
        null,
        _t('Script鏍囩'),
        _t('浣嶄簬Footer锛屽湪杩欓噷濉叆JavaScript浠ｇ爜锛岄渶瑕佸寘鍚?lt;script&gt;鏍囩锛侊紝涓嶈濉櫎浜嗚剼鏈鐨勫叾浠栧唴瀹癸紝鍚﹀垯浼氶€犳垚鏍峰紡閿欒锛?br>濡傛灉寮€鍚簡 Pjax 鍔熻兘锛岃鑷鍦?header.php 閰嶇疆鍥炶皟鍑芥暟鎴栬€呭皾璇曞姹傚府鍔?)
    );
    $form->addInput($footerScript);

    $staticCdn = new Typecho_Widget_Helper_Form_Element_Radio(
        'staticCdn',
        array(
            'local' => _t('鏈湴'),
            'bootcdn' => _t('BootCDN'),
            'cdnjs' => _t('CDNJS'),
        ),
        'local',
        _t("涓婚闈欐€佽祫婧?CDN"),
        _t("闈欐€佽祫婧?CDN 婧愰€夋嫨锛岄粯璁や负鏈湴")
    );
    $form->addInput($staticCdn);

    // 缃戦〉搴曢儴淇℃伅
    $footerInfo = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'footerInfo',
        null,
        '鍦?Typecho 鍚庡彴涓婚璁剧疆濉啓姝ゅ瀛楁<br>鎰熻阿浣跨敤 PureSuck 涓婚',
        _t('缃戦〉搴曢儴淇℃伅'),
        _t('濉啓缃戦〉搴曢儴鐨勮嚜瀹氫箟淇℃伅锛屽彲浠ュ寘鍚獺TML鍐呭锛岀敤br鏍囩鎹㈣')
    );
    $form->addInput($footerInfo);


    // Pjax鍥炶皟鍑芥暟锛圫wup锛?    $PjaxScript = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'PjaxScript',
        null,
        null,
        _t('Swup 鍥炶皟鍑芥暟'),
        _t('鍦ㄨ繖閲屽～鍏ラ渶瑕佸湪姣忔椤甸潰鍒囨崲鍚庢墽琛岀殑鍑芥暟锛屼緥濡傦細loadDPlayer(); 濡傛灉涓嶇煡閬撹繖鏄粈涔堬紝璇峰拷鐣ャ€?)
    );
    $form->addInput($PjaxScript);

    //涓婚鏍峰紡缁嗚皟
    // 鏍囬绮椾笅鍒掔嚎
    $postTitleAfter = new Typecho_Widget_Helper_Form_Element_Radio(
        'postTitleAfter',
        array(
            'off' => _t('鍏?),
            'boldLine' => _t('绮楃嚎鏉?),
            'wavyLine' => _t('娉㈡氮绾挎潯'),
        ),
        'off',
        _t('涓绘爣棰樹笅鐨勮楗扮嚎鏉℃牱寮?),
        _t('閫夋嫨涓绘爣棰樹笅鐨勮楗扮嚎鏉℃牱寮忥紝甯︽湁瑙︽懜鍙嶉')
    );
    $form->addInput($postTitleAfter);

    // 鎼滅储鍔熻兘鏄剧ず閫夐」
    $showSearch = new Typecho_Widget_Helper_Form_Element_Radio(
        'showSearch',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鏄剧ず鎼滅储鍔熻兘'),
        _t('鍦ㄩ〉闈㈠彸渚ф樉绀轰竴涓悳绱㈡')
    );
    $form->addInput($showSearch);

    // TOC 妯″潡鏄剧ず閫夐」
    $showTOC = new Typecho_Widget_Helper_Form_Element_Radio(
        'showTOC',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鏄剧ず TOC 鐩綍鏍?),
        _t('鍦ㄩ〉闈㈠彸渚ф樉绀轰竴涓洰褰曟爲锛屽鏋滈〉闈㈡病鏈夊搴旂殑鐩綍缁撴瀯浼氳嚜鍔ㄩ殣钘?)
    );
    $form->addInput($showTOC);

    // 鍒嗙被妯″潡鏄剧ず閫夐」
    $showCategory = new Typecho_Widget_Helper_Form_Element_Radio(
        'showCategory',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鏄剧ず鍒嗙被妯″潡')
    );
    $form->addInput($showCategory);

    // 鏍囩妯″潡鏄剧ず閫夐」
    $showTag = new Typecho_Widget_Helper_Form_Element_Radio(
        'showTag',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鏄剧ず鏍囩妯″潡')
    );
    $form->addInput($showTag);

    // 鏂囩珷椤垫樉绀哄瓧鏁颁俊鎭€夐」
    $showWordCount = new Typecho_Widget_Helper_Form_Element_Radio(
        'showWordCount',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鍦ㄦ枃绔犲紑澶存樉绀哄瓧鏁板拰棰勮闃呰鏃堕棿')
    );
    $form->addInput($showWordCount);

    // 鏂囩珷椤垫樉绀虹増鏉冧俊鎭€夐」
    $showCopyright = new Typecho_Widget_Helper_Form_Element_Radio(
        'showCopyright',
        array('1' => _t('鏄剧ず'), '0' => _t('闅愯棌')),
        '1',
        _t('鏄惁鍦ㄦ枃绔犻〉灏炬樉绀虹増鏉冧俊鎭?)
    );
    $form->addInput($showCopyright);

    $ccLicense = new Typecho_Widget_Helper_Form_Element_Radio(
        'ccLicense',
        array(
            'by-nc-sa' => _t('CC BY-NC-SA 4.0'),
            'by-nc' => _t('CC BY-NC 4.0'),
            'by' => _t('CC BY 4.0'),
            'by-sa' => _t('CC BY-SA 4.0'),
            'by-nc-nd' => _t('CC BY-NC-ND 4.0'),
            'zero' => _t('CC0 1.0'),
        ),
        'by-nc-sa',
        _t('浣跨敤鐨凜C鍗忚'),
        _t('閫夋嫨浣跨敤鐨凜C鍗忚锛岄粯璁や负CC BY-NC-SA 4.0')
    );
    $form->addInput($ccLicense);

    // 涓婚閰嶈壊
    $colors = array(
        'pink' => _t('绱犵矇'),
        'green' => _t('娣＄豢'),
        'blue' => _t('澧ㄨ摑'),
        'yellow' => _t('钀介粍'),
        'red' => _t('璧ょ孩'),
        'purple' => _t('骞界传'),
        'cyan' => _t('闈掔┖'),
        'orange' => _t('姗欓槼'),
    );
    $defaultColor = 'pink';
    $colorSelect = new Typecho_Widget_Helper_Form_Element_Radio('colorScheme', $colors, $defaultColor, _t('閰嶈壊鏂规'), _t('閫夋嫨涓€涓厤鑹叉柟妗堬紝榛樿涓虹礌绮?));
    $form->addInput($colorSelect);

    // 鍗＄墖绔栨帓鍒嗙被鏄剧ず閫夐」
    $showCardCategory = new Typecho_Widget_Helper_Form_Element_Radio(
        'showCardCategory',
        array(
            '1' => _t('鏄剧ず'),
            '0' => _t('闅愯棌')
        ),
        '1',
        _t('鏄惁鍦ㄦ枃绔犲崱鐗囧彸涓婅鏄剧ず绔栨帓鍒嗙被'),
        _t('鍙湪棣栭〉鍙婃悳绱㈤〉绛夊皬鍗＄墖鏄剧ず锛屼笉鍦ㄦ枃绔犲唴鏄剧ず')

    );
    $form->addInput($showCardCategory);

}

