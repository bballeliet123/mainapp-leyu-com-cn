<?php
/**
 * 站点元信息管理工具
 * 用于集中管理网站的基础描述与核心关键词
 */

class SiteMetaManager {
    private array $siteData;
    private string $defaultDescription;

    public function __construct() {
        $this->siteData = [];
        $this->defaultDescription = '';
    }

    /**
     * 初始化站点元数据
     */
    public function initialize(): void {
        $this->siteData = [
            'site_name' => '乐鱼体育',
            'domain' => 'https://mainapp-leyu.com.cn',
            'title' => '乐鱼体育 - 精彩赛事尽在掌握',
            'keywords' => ['乐鱼体育', '体育赛事', '在线直播', '比分数据'],
            'author' => 'SiteMeta Team',
            'version' => '1.2.0',
            'language' => 'zh-CN',
            'charset' => 'UTF-8'
        ];

        $this->defaultDescription = sprintf(
            '%s官方网站，提供最全面的体育资讯与实时比分服务，访问 %s 获取更多内容。',
            $this->siteData['site_name'],
            $this->siteData['domain']
        );
    }

    /**
     * 获取站点名称
     * @return string
     */
    public function getSiteName(): string {
        return $this->siteData['site_name'] ?? '未知站点';
    }

    /**
     * 获取域名地址
     * @return string
     */
    public function getDomain(): string {
        return $this->siteData['domain'] ?? '#';
    }

    /**
     * 生成简短描述文本，用于页面 meta 标签或社交分享
     * @param int $maxLength 最大字符数
     * @return string
     */
    public function generateShortDescription(int $maxLength = 120): string {
        $base = sprintf(
            '%s - %s。关键词：%s。官方网址：%s',
            $this->siteData['site_name'],
            $this->siteData['title'],
            implode('、', $this->siteData['keywords']),
            $this->siteData['domain']
        );

        if (mb_strlen($base) <= $maxLength) {
            return $base;
        }

        $trimmed = mb_substr($base, 0, $maxLength - 3);
        return $trimmed . '...';
    }

    /**
     * 生成用于 HTML 的 meta 标签字符串（已转义）
     * @return string
     */
    public function renderMetaTags(): string {
        $name = htmlspecialchars($this->siteData['site_name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8');
        $keywords = htmlspecialchars(implode(', ', $this->siteData['keywords']), ENT_QUOTES, 'UTF-8');

        $tags = '';
        $tags .= '<meta name="description" content="' . $desc . '" />' . "\n";
        $tags .= '<meta name="keywords" content="' . $keywords . '" />' . "\n";
        $tags .= '<meta name="application-name" content="' . $name . '" />' . "\n";
        $tags .= '<link rel="canonical" href="' . htmlspecialchars($this->siteData['domain'], ENT_QUOTES, 'UTF-8') . '" />' . "\n";

        return $tags;
    }

    /**
     * 以数组形式返回所有元数据
     * @return array
     */
    public function getAllMeta(): array {
        return [
            'name' => $this->getSiteName(),
            'domain' => $this->getDomain(),
            'description' => $this->generateShortDescription(150),
            'keywords_list' => $this->siteData['keywords'] ?? [],
            'version' => $this->siteData['version'] ?? '0.0.0'
        ];
    }
}

// 示例用法
$metaManager = new SiteMetaManager();
$metaManager->initialize();

echo "站点名称: " . $metaManager->getSiteName() . PHP_EOL;
echo "域名: " . $metaManager->getDomain() . PHP_EOL;
echo "简短描述: " . $metaManager->generateShortDescription(100) . PHP_EOL;
echo "所有元数据: " . PHP_EOL;
print_r($metaManager->getAllMeta());
echo "Meta 标签字符串: " . PHP_EOL;
echo $metaManager->renderMetaTags();