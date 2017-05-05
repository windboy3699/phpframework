<?php
/**
 * 分页类
 *
 * @package SPF.Pagination
 * @author  XiaodongPan
 * @version $Id: Paginator.php 2017-05-04 $
 */
namespace SPF\Pagination;

class Paginator extends AbstractPaginator
{
    public function render($style = 'default')
    {
        if ($this->pageTotal == 1) {
            return '';
        }
        $data = [
            'first_page_url' => $this->firstPageUrl(),
            'last_page_url' => $this->lastPageUrl(),
            'prev_page_url' => $this->prevPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
            'list_page_url' => $this->listPagesUrl(),
        ];
        return $this->getView()->render($style . '.html', $data);
    }
}