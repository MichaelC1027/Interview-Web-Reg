<?php
class Navigate{
    public function __construct($page)
    {
        echo json_encode(['navigate' => $page]);
        exit();
    }
}