<?php
namespace classes;
use application\core\config\config;
use PDO;
class Pagination
{
    public $php_self, $append, $sql, $name_page;
	public $rows_per_page = 10;
	public $total_rows, $max_pages, $offset = 0;
	public $links_per_page = 5;
	public $page = 1;
    private $conn = false;

    function __construct($sql, $rows_per_page = 10, $links_per_page = 5, $append = "")
    {
        $this->conn = Config::GetInstance();
		$this->sql = $sql;
		$this->rows_per_page = (int)$rows_per_page;
        $this->links_per_page = (intval($links_per_page ) > 0) ? (int)$links_per_page : 5;

		$this->append = $append;
        if(isset($_POST['page']))
            $this->page = intval($_POST['page']);
	}
    public function PaginateAdvert()
    {
		$all_rs = $this->conn->dbh->prepare( $this->sql );
		$all_rs->execute();
		$this->total_rows = $all_rs->rowCount();
		if ($this->total_rows == 0) return false;

		$this->max_pages = ceil($this->total_rows / $this->rows_per_page );
		if ($this->links_per_page > $this->max_pages)
			$this->links_per_page = $this->max_pages;
		

		if ($this->page > $this->max_pages || $this->page <= 0)
            $this->page = 1;
		

		$this->offset = $this->rows_per_page * ($this->page - 1);

		$query = $this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}";
		$rs = $this->conn->dbh->prepare( $query );
		$rs->execute();
		return $rs;
	}
	
	public function RenderFullNav()
    {
		return $this->RenderPrev() . '&nbsp;' . $this->RenderNav() . '&nbsp;' . $this->RenderNext();
	}

    private function RenderFirst($tag = 'Начало')
    {
        if ($this->total_rows == 0)
            return false;
        return ($this->page == 1) ? $tag : "<a href='javascript:pageNext( " . $this->name_page . ");' title='Первая страница'>$tag</a> ";
    }

    private function RenderLast($tag = 'Конец')
    {
        if ($this->total_rows == 0)
            return false;
        return ($this->page == $this->max_pages) ? $tag : "<a href='javascript:pageNext( " . $this->max_pages . ");' title='Последняя страница'>$tag</a> ";
    }

    private function RenderNext($tag = '&gt;&gt;')
    {
        if ($this->total_rows == 0)
            return false;
        $pageNext = $this->page + 1;
        return ($this->page < $this->max_pages) ? "<a href='javascript:pageNext(" . $pageNext .");' style='color: black;' title='Следующая страница'><b> >> </b></a> " : "";
    }

    private function RenderPrev($tag = '&lt;&lt;')
    {
        if ($this->total_rows == 0)
            return false;
        $pagePrev = $this->page - 1;
        return ($this->page > 1) ? "<a style='position:relative; left: 7px; color:black;' href='javascript:pageNext(" . $pagePrev .");'  title='Предыдущая страница'><b> << </b></a>" : "";
    }

    private function RenderNav($prefix = '<span class="page_link">', $suffix = '</span>')
    {
        if ($this->total_rows == 0)
            return false;
        $batch = ceil($this->page / $this->links_per_page );
        $end = $batch * $this->links_per_page;
        if ($end > $this->max_pages)
            $end = $this->max_pages;

        $start = $end - $this->links_per_page + 1;
        $links = '';
        for($i = $start; $i <= $end; $i ++)
        {
            $page =  ($this->rows_per_page*$i-$this->rows_per_page + 1) . '-' . $this->rows_per_page*$i;
            $links .= ($i == $this->page) ? "<b class='active-navigation'>" . $prefix . " $page " . $suffix . "</b>"
                : "<a class='other-navigation' href='javascript:pageNext(". $i .");' ><b>$page</b></a>";
        }
        return $links;
    }
}
?>
