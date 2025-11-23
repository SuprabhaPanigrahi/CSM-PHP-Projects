<?php
include_once 'book.php';
include_once 'member.php';
include_once 'library.php';
class Test{
      
    public function runLibrary(){
        $csmLibrary = new Library();

        $flag =true;
        do{
            echo "1.Manage Books\n";
            echo "2.Manage Members\n";
            echo "3.Issue Books\n";
            echo "4.Receive Books\n";
            echo "5.See Books Borrwed by Members\n";
            echo "6.Exit\n";
            echo "Enter your choice: ";
            $choice = trim(readline());
            
            switch ($choice) {
                case '1':
                    #Add code to manage books
                    $this->manageBooks($csmLibrary);
                    break;
                case '2':
                    # Add code to manage members
                    $this->manageMembers($csmLibrary);
                    break;
                case '3':
                    # Add code to Issue books
                    $this->issueBooks($csmLibrary); 
                    break;
                case '4':
                    # Add code to manage members
                    $this->receiveBooks($csmLibrary);
                    break;
                case '5':
                    $this->displayIssuedBooks($csmLibrary);
                    # Add code to manage members
                    break;
                case '6':
                    # Add code to manage members
                    $flag = false;
                    break;
                default:
                    echo "Invalid choice. Please try again.\n";
                    break; 
            }
            echo "Do you want to continue? (yes/no): ";
            $continueChoice = trim(readline());
            if (strtolower($continueChoice) !== 'yes') {
                $flag = false;
            }
        }while($flag);
        echo "Exiting Library Management System. Goodbye!\n";
    }

    private function manageBooks($library){
        $flag = true;
        do{
            echo "1.Add Book\n";
            echo "2.Remove Book\n";
            echo "3.Search Book\n";
            echo "4.View Books\n";
            echo "5.Back to Main Menu\n";
            echo "Enter your choice: ";
            $choice = trim(readline());
            switch ($choice) {
                case '1':
                    # Add code to add book
                    echo "Enter Book Title: ";
                    $title = trim(readline());
                    echo "Enter Book Author: ";
                    $author = trim(readline());
                    echo "Enter Book Availability: ";
                    $availability = trim(readline());
                    $book = new Book($title, $author, $availability);
                    $library->addBook($book);
                    echo "Book '{$title}' by '{$author}' added to the library.\n";
                    break;
                case '2':
                    # Add code to remove book
                    echo "Enter Book Title to Remove: ";
                    $titleToRemove = trim(readline());
                    $result = $library->removeBook($titleToRemove);
                    echo $result . "\n";
                    break;
               
                case '3':
                    # Add code to search book
                    echo "Enter Book Title to Search: ";
                    $titleToSearch = trim(readline());
                    $foundBook = $library->searchBook($titleToSearch);
                    if ($foundBook) {
                        echo "Book Found: Title: {$foundBook->getTitle()}, Author: {$foundBook->getAuthor()}, Availability: {$foundBook->getAvailability()}\n";
                    } else {
                        echo "Book '{$titleToSearch}' not found in the library.\n";
                    }
                    break;
                 case '4':
                    # Add code to display books
                    echo "Books in the Library:\n";
                    $library->displayBooks();
                    break;
                case '5':
                    # Back to main menu
                    $flag = false;
                    break;
                default:
                    echo "Invalid choice. Please try again.\n";
                    break;
                }
            
        }while($flag);
    }

    private function manageMembers($library){
        $flag = true;
        do{
            echo "1.Add Member\n";
            echo "2.Remove Member\n";
            echo "3.Search Member\n";
            echo "4.View Members\n";
            echo "5.Back to Main Menu\n";
            echo "Enter your choice: ";
            $choice = trim(readline());
            switch ($choice) {
                case '1':
                    # Add code to add member
                    echo "Enter Member Name: ";
                    $name = trim(readline());
                    echo "Enter Member ID: ";
                    $id = trim(readline());
                
                    $member = new Member($name, $id);
                    $library->addMember($member);
                    echo "Member '{$name}' with ID '{$id}' added to the library.\n";
                    break;
                case '2':
                    # Add code to remove member
                    echo "Enter Member ID to Remove: ";
                    $idToRemove = trim(readline());
                    $result = $library->removeMember($idToRemove);
                    echo $result . "\n";
                    break;
               
                case '3':
                    # Add code to search member
                    echo "Enter Member ID to Search: ";
                    $idToSearch = trim(readline());
                    $foundMember = $library->searchMember($idToSearch);
                    if ($foundMember) {
                        echo "Member Found: Name: {$foundMember->getName()}, ID: {$foundMember->getId()}\n";
                    } else {
                        echo "Member '{$idToSearch}' not found in the library.\n";
                    }
                    break;
                 case '4':
                    # Add code to display members
                    echo "Members in the Library:\n";
                    $library->displayMembers();
                    break;
                case '5':
                    # Back to main menu
                    $flag = false;
                    break;
                default:
                    echo "Invalid choice. Please try again.\n";
                    break;
                }
            
        }while($flag);
    }

    private function issueBooks($library){
        // Code to issue books to members
        $memberId = readline("Enter Member ID: ");
        $bookTitle = readline("Enter Book Title to Issue: ");
        $result = $library->issueBook($memberId, $bookTitle);
        echo $result . "\n";
    }
    private function receiveBooks($library){
        // Code to receive returned books from members
        $memberId = readline("Enter Member ID: ");
        $bookTitle = readline("Enter Book Title to Return: ");
        $result = $library->receiveBook($memberId, $bookTitle);
        echo $result . "\n";
    }
    private function displayIssuedBooks($library){
        // Code to display issued books
        echo "Issued Books:\n";
        $library->displayIssuedBooks();
    }
}
$test = new Test();
$test->runLibrary();
