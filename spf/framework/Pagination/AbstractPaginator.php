<?php
/**
 * Pagination Abstract
 *
 * @package SPF.Pagination
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
     * 每页显示条目数量
     *
     * @var int
     */
    protected $perPage;

    /**
     * 当前页码(第几页)
     *
     * @var int
     */
    protected $currentPage;

    /**
     * 页码参数名称
     *
     * @var string
     */
    protected $pageName;

    /**
     * 条目可分总页数
     *
     * @var int
     */
    protected $pageTotal;

    /**
     * 显示的分页个数
     *
     * @var int
     */
    protected $pageSize;

    /**
     * @var View
     */
    protected $view = null;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param $total 条目总数
     * @param int $perPage 每页显示条目数
     * @param int $pageSize 显示页码数量
     * @param string $pageName 页码参数名
     */
    public function __construct($total, $perPage = 20, $pageSize = 7, $pageName = 'p')
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->pageSize = $pageSize;
        $this->pageName = $pageName;
        $this->pageTotal = ceil($total/$perPage);
        $this->currentPage = $this->getCurrentPage();
        $this->uri = $this->getUri();
    }

    abstract public function render();

    private function getCurrentPage()
    {
        $page = isset($_GET[$this->pageName]) ? (int)$_GET[$this->pageName] : 1;
        $page > 0 || $page = 1;
        $page > $this->pageTotal && $page = $this->pageTotal;
        return $page;
    }

    private function getUri()
    {
        $uriArray = parse_url($_SERVER["REQUEST_URI"]);
        if (isset($uriArray['query'])) {
            parse_str($uriArray['query'], $query);
            unset($query[$this->pageName]);
            while ($key = array_search('', $query)) {
                unset($query[$key]);
            }
            $uri = $uriArray['path'] . '?' . http_build_query($query);
        } else {
            $uri = $uriArray['path'] . '?';
        }
        strpos($uri, '=') !== false && $uri .= '&';
        return $uri;
    }

    protected function firstPageUrl()
    {
        return $this->currentPage == 1 ? '' : $this->buildUrl(1);
    }

    protected function lastPageUrl()
    {
        return $this->currentPage == $this->pageTotal ? '' : $this->buildUrl($this->pageTotal);
    }

    protected function prevPageUrl()
    {
        return $this->currentPage == 1 ? '' : $this->buildUrl($this->currentPage - 1);
    }

    protected function nextPageUrl()
    {
        return $this->currentPage == $this->pageTotal ? '' : $this->buildUrl($this->currentPage + 1);
    }

    protected function buildUrl($page)
    {
        return $this->uri . $this->pageName . '=' . $page;
    }

    protected function listPagesUrl()
    {
        if ($this->pageTotal <= $this->pageSize) {
            $start = 1;
            $stop = $this->pageTotal;
        } else {

            $leftlength = floor(($this->pageSize - 1)/2);

            $start = $this->currentPage - $leftlength;
            $start < 1 && $start = 1;

            $haslength = $this->currentPage - $start + 1;
            $stop = $this->currentPage + ($this->pageSize - $haslength);

            if ($stop > $this->pageTotal) {
                $stop = $this->pageTotal;
                $start = $this->pageTotal - $this->pageSize + 1;
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

    protected function getView()
    {
        if ($this->view === null) {
            $this->view = View::create(dirname(__FILE__) . '/view/');
        }
        return $this->view;
    }
}
