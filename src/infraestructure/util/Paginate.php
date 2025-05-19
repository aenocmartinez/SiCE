<?php

namespace Src\infraestructure\util;


class Paginate {

    private $page;
    private $limit;
    private $offset;
    private $totalRecords;
    private $records;

    public function __construct($page = 1, $itemsPerPage = 0) {
        $this->page = max(1, (int) $page); // asegura página válida

        $this->limit = ($itemsPerPage != 0)
            ? (int) $itemsPerPage
            : (int) env('APP_PAGINADOR_NUM_ITEMS', 20); // valor por defecto seguro

        $this->offset = ($this->page - 1) * $this->limit;
        $this->records = [];
    }


    // public function __construct($page=1, $itemsPerPage=0) {        
    //     $this->page = $page;        

    //     $this->limit = ($itemsPerPage != 0) ? $itemsPerPage :  env('APP_PAGINADOR_NUM_ITEMS');
        
    //     $this->offset = ($this->page - 1) * $this->limit;        
    //     $this->records = array();
    // }

    public function Limit() {
        return $this->limit;
    }

    public function Offset() {  
        return $this->offset;
    }

    public function setRecords($records=[]) {
        $this->records = $records;
    }

    public function Records(): array {
        return $this->records;
    }    

    public function setTotalRecords($totalRecords) {
        $this->totalRecords = $totalRecords;
    }

    public function TotalRecords() {
        return $this->totalRecords;
    }

    public function Page() {
        return $this->page;
    }

    public function NumberOfPages() {        
        $numberOfpages = intval($this->totalRecords / $this->limit);

        if ($this->totalRecords % $this->limit != 0) {
            $numberOfpages += 1;
        }
        
        return $numberOfpages;
    }

    public function Next() {
        return $this->page + 1;
    }

    public function Previous() {
        return $this->page - 1;
    }

    public function First() {
        return 1;
    }

    public function Last() {
        return $this->NumberOfPages();
    }

    public function IsFirst() {
        return $this->page == 1;
    }

    public function IsLast() {
        return $this->page == $this->Last();
    }    
}