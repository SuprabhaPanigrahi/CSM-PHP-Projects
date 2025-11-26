<?php
class Member {
    public $name;
    public $memberId;
    public $borrowedItems = [];

    public function __construct($name, $memberId) {
        $this->name = $name;
        $this->memberId = $memberId;
    }

    public function borrowItem($item) {
        if ($item->isAvailable) {
            $item->borrowItem();
            $this->borrowedItems[] = $item;
        } else {
            echo "Item not available to borrow.\n";
        }
    }

    public function returnItem($item) {
        foreach ($this->borrowedItems as $key => $borrowed) {
            if ($borrowed === $item) {
                $item->returnItem();
                unset($this->borrowedItems[$key]);
                return;
            }
        }
        echo "This item was not borrowed by the member.\n";
    }

    public function displayMemberDetails() {
        echo "Member: {$this->name}, ID: {$this->memberId}\n";
        echo "Borrowed Items:\n";
        if (empty($this->borrowedItems)) {
            echo "- None\n";
        } else {
            foreach ($this->borrowedItems as $item) {
                echo "- {$item->title}\n";
            }
        }
    }
}
?>
