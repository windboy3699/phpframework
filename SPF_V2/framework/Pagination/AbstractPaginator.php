<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AbstractPaginator.php 2017-05-04 $
 */
namespace SPF\Pagination;

use SPF\View\View;

abstract class AbstractPaginator
{
    /**
     * 条目总数
     *
     * @var int
     */
    protected $total;

    /**
     * 每页数量
     *
     * @var int
     */
    protected $perPage;

    /**
     * 当前页码
     *
     * @var int
     */
    protected $currentPage;

    /**
     * 页数参数名称
     *
     * @var string
     */
    protected $pageName;

    /**
     * 总页数
     *
     * @var int
     */
    protected $pageTotal;

    /**
     * 显示的分页数量
     *
     * @var int
     */
    protected $pageLength;

    protected $view = null;

    protected $uri;

    /**
     * Create a new paginator instance.
     *
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  string  $pageName
     * @return void
     */
    public function __construct($total, $perPage = 10, $pageLength = 7, $pageName = 'p')
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->pageLength = $pageLength;
        $this->pageName = $pageName;
        $this->pageTotal = ceil($total/$perPage);
        $this->currentPage = $this->getCurrentPage();
        $this->uri = $this->getUri();
    }

    public function render()
    {
        if ($this->pageTotal == 1) {
            return '';
        }
        $data = [
            'first_page_url' => $this->firstPageUrl(),
            'last_page_url' => $this->lastPageUrl(),
            'prev_page_url' => $this->prevPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
            'multiple_pages_url' => $this->multiplePagesUrl(),
        ];
        return $this->getView()->render('default.html', $data);
    }

    public function getCurrentPage()
    {
        $page = isset($_GET[$this->pageName]) ? (int)$_GET[$this->pageName] : 1;
        $page > 0 || $page = 1;
        $page > $this->pageTotal && $page = $this->pageTotal;
        return $page;
    }

    public function firstPageUrl()
    {
        return $this->currentPage == 1 ? '' : $this->buildUrl(1);
    }

    public function lastPageUrl()
    {
        return $this->currentPage == $this->pageTotal ? '' : $this->buildUrl($this->pageTotal);
    }

    public function prevPageUrl()
    {
        return $this->currentPage == 1 ? '' : $this->buildUrl($this->currentPage - 1);
    }

    public function nextPageUrl()
    {
        return $this->currentPage == $this->pageTotal ? '' : $this->buildUrl($this->currentPage + 1);
    }

    public function buildUrl($page)
    {
        return $this->uri . $this->pageName . '=' . $page;
    }

    public function multiplePagesUrl()
    {
        if ($this->pageTotal <= $this->pageLength) {
            $start = 1;
            $stop = $this->pageTotal;
        } else {

            $leftlength = floor(($this->pageLength - 1)/2);

            $start = $this->currentPage - $leftlength;
            $start < 1 && $start = 1;

            $haslength = $this->currentPage - $start + 1;
            $stop = $this->currentPage + ($this->pageLength - $haslength);

            if ($stop > $this->pageTotal) {
                $stop = $this->pageTotal;
                $start = $this->pageTotal - $this->pageLength + 1;
            }
        }

        $data = [];
        for ($i = $start; $i <= $stop; $i++) {
            $data[] = [
                'num' => $i,
                'url' => $this->buildUrl($i),
                'active' => $this->currentPage == $i ? 1 : 0,
            ];
        }
        return $data;
    }

    /**
     * 处理获取新的url
     *
     * @return string
     */
    private function getUri()
    {
        $url = $_SERVER["REQUEST_URI"];
        $parseArray = parse_url($url);
        if (isset($parseArray['query'])) {
            parse_str($parseArray['query'], $query);
            $uriArray = array();
            array_merge($query, $uriArray);
            unset($query[$this->pageName]);
            while ($key = array_search('', $query)) {
                unset($query[$key]);
            }
            $uri = $parseArray['path'] . '?' . http_build_query($query);
        } else {
            $uri = $parseArray['path'] . '?';
        }
        if (strpos($uri, '=') !== false) {
            $uri .= '&';
        }
        return $uri;
    }

    /**
     * 获取模板引擎
     *
     * @return Twig_Environment
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = View::create(dirname(__FILE__) . '/view/');
        }
        return $this->view;
    }
}
