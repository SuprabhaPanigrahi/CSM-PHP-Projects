<?php
class Member {
    public $id;
    public $name;
    public $member_id;

    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->member_id = $row['member_id'];
    }
}
