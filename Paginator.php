<?php 
class Paginator{
    private $conn;
    private $limit;
    private $page;
    private $query;
    private $total;
    private $twig;

    public function __construct($twig, $conn, $query){
        $this->limit = $limit;
        $this->page = $page;

        if($this->limit == 'all'){
            $query = $this->query;
        } else{
            $offset = (($this->page - 1) * $this->limit);
            $query = $this->query . " LIMIT " . $offset . ", $this->limit";
        }
        $rs = $this->conn->query($query);

        while($row = $rs->fetch_assoc()){
            $result[] = $row;
        }

        $result         = new stdClass();
        $result->page   = $this->page;
        $result->limit  = $this->limit;
        $result->total  = $this->total;
        $result->data   = $result;

        return $result;
    }

    public function createLinks(){
        if($this->limit == 'all'){
            return '';
        }

        $first      = 1;
        $last       = ceil($this->total / $this->limit);

        $html = $this->twig->render('shared/pagination/index.html.twig',[
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->totalm,
            'first' => $first,
            'last' => $last    
        ]);
        return $html;
    }
}

?>