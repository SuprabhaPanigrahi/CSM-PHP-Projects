<?php
class BankAccount {
    private $accountNumber;
    private $balance;
    public $transactions = [];

    public function __construct($accountNumber, $balance = 0) {
        $this->accountNumber = $accountNumber;
        $this->balance = $balance;
    }

    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
            $this->transactions[] = "Deposited: ₹$amount";
            echo "Deposit Successful<br>";
        } else {
            echo "Invalid Amount<br>";
        }
    }

    public function withdraw($amount) {
        if ($amount <= $this->balance) {
            $this->balance -= $amount;
            $this->transactions[] = "Withdrawn: ₹$amount";
            echo "Withdrawal Successful<br>";
        } else {
            echo "Insufficient Balance<br>";
        }
    }

    public function getBalance() {
        return $this->balance;
    }
}

// Demo
$acc = new BankAccount(101, 500);
$acc->deposit(200);
$acc->withdraw(100);
echo "Balance: " . $acc->getBalance();
?>
