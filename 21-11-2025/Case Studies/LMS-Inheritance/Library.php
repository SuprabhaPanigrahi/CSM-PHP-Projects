<?php
class Library {
    public $items = [];
    public $members = [];

    public function addItem($item) {
        $this->items[] = $item;
        echo "Item added: {$item->title}\n";
    }

    public function removeItem($title) {
        foreach ($this->items as $key => $item) {
            if ($item->title === $title) {
                unset($this->items[$key]);
                echo "Item removed: $title\n";
                return;
            }
        }
        echo "Item not found: $title\n";
    }

    public function addMember($member) {
        $this->members[] = $member;
        echo "Member added: {$member->name}\n";
    }

    public function removeMember($memberId) {
        foreach ($this->members as $key => $member) {
            if ($member->memberId === $memberId) {
                unset($this->members[$key]);
                echo "Member removed: $memberId\n";
                return;
            }
        }
        echo "Member not found: $memberId\n";
    }
}
?>
