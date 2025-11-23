<?php

echo "Complete File Operations in PHP:\n";
$filename ="";
echo "\n1. Creating and Writing to a File:\n";
echo "\n2. Reading from a File Line by Line:\n";
echo "\n3. Appending Data to a File:\n";
echo "\n4. Renaming a File:\n";
echo "\n5. Deleting a File:\n";
echo "\n6. Checking File Existence and Size:\n";
echo "\n7. Counting Total Characters in a File:\n";
echo "\n8. Counting Total Words in a File:\n";
echo "\n9. Counting Total Lines in a File:\n";
echo "\n10.Replacement of Text in a File:\n";
echo "\n11. File Permissions Handling:\n";
echo "\n12.Copying a File:\n";
echo "\n13.Moving a File:\n";
echo "\n14. File Locking Mechanism:\n";
echo "\n15. Storing and Retrieving Serialized Data:\n";
echo "\n16. Handling File Uploads:\n";
echo "\n17. Reading and Writing CSV Files:\n";
echo "\n18. Working with JSON Files:\n";
echo "\n19. Storing and retrieving associative arrays:\n";

echo "\nEnter your choice :\n";
$choice = readline();

switch($choice){
  case 1:
    createFile();
    break;
    case 2:
    readFileLineByLine();
    break;
    case 3:
    appendDataToFile();
    break;
    case 4:
    renameFile();
    break;
    case 5:
    deleteFile();
    break;
    case 6:
    checkFileExistenceAndSize();
    break;
    case 7:
    countTotalCharactersInFile();
    break;
    case 8:
    countTotalWordsInFile();
    break;
    case 9:
    countTotalLinesInFile();
    break;
    case 10:
    replaceTextInFile();
    break;
    case 11:
    handleFilePermissions();
    break;
    case 12:
    copyFile();
    break;
    case 13:
    moveFile();
    break;
    case 14:
    implementFileLocking();
    break;
    case 15:
    storeAndRetrieveSerializedData();
    break;
    case 16:
    handleFileUploads();
    break;
    case 17:
    readAndWriteCSVFiles();
    break;
    case 18:
    workWithJSONFiles();
    break;
    case 19:
    storeAndRetrieveAssociativeArrays();
    break;  
  default:
    echo "Invalid Choice";  
}

 function createFile(){
    echo "Enter filename to create: ";
    $filename = readline();
    $file = fopen($filename, "w");
    if ($file !== false) {
        echo "File created successfully.\n";
        echo "Enter content to write to the file: ";
        $content = readline();
        fwrite($file, $content);
        echo "Content written to the file successfully.\n";
        fclose($file);
    } else {
        echo "Error creating file.\n";
    }
 }

 function readFileLineByLine(){
    $filename = "note.txt";
    if(file_exists($filename)){
        $file = fopen($filename, "r");
        if ($file === false) {
            die("Error: Unable to open the file.");
        }
        if( filesize($filename) > 0 ) {
            $lineCount =0;
            while(!feof($file)){
                $line = fgets($file);
                $lineCount++;
                echo "Line {$lineCount}: " . $line . "\n";
            }
            echo "Total Lines: " . $lineCount;
      
        } 
        else{    
            die("Error: File is empty.");
        }
        fclose($file);
    } else {
        die("Error: File does not exist.");
    }
 }

 function appendDataToFile(){
    $filename = "note.txt";
    $file = fopen($filename, "a");
    if ($file === false) {
        die("Error: Unable to open the file.");
    }
    $data = readline("Enter data to append to the file: ");
    fwrite($file, $data . PHP_EOL); //EOL - END OF LINE
    echo "Data appended successfully.\n";
    fclose($file);
 }

 function renameFile(){
    echo "Enter current filename: ";
    $currentName = readline();
    echo "Enter new filename: ";
    $newName = readline();
    if (file_exists($currentName)) {
        if (rename($currentName, $newName)) {
            echo "File renamed successfully to {$newName}.\n";
        } else {
            echo "Error renaming file.\n";
        }
    } else {
        echo "File does not exist.\n";
    }
 }

 function deleteFile(){
    echo "Enter filename to delete: ";
    $filename = readline();
    if (file_exists($filename)) {
        if (unlink($filename)) {
            echo "File deleted successfully.\n";
        } else {
            echo "Error deleting file.\n";
        }
    } else {
        echo "File does not exist.\n";
    }
 }

 function checkFileExistenceAndSize(){
    echo "Enter filename to check: ";
    $filename = readline();
    if (file_exists($filename)) {
        $size = filesize($filename);
        echo "File exists. Size: {$size} bytes.\n";
    } else {
        echo "File does not exist.\n";
    }
 }

 function countTotalCharactersInFile(){
    $filename = "note.txt";
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $charCount = strlen($content);
        echo "Total characters in the file: {$charCount}\n";
    } else {
        echo "File does not exist.\n";
    }
 }

  function countTotalWordsInFile(){
      $filename = "note.txt";
      if (file_exists($filename)) {
          $content = file_get_contents($filename);
          $wordCount = str_word_count($content);
          echo "Total words in the file: {$wordCount}\n";
      } else {
          echo "File does not exist.\n";
      }
  } 

  function countTotalLinesInFile(){
      $filename = "note.txt";
      if (file_exists($filename)) {
          $file = fopen($filename, "r");
          $lineCount = 0;
          while(!feof($file)){
              fgets($file);
              $lineCount++;
          }
          fclose($file);
          echo "Total lines in the file: {$lineCount}\n";
      } else {
          echo "File does not exist.\n";
      }
  }

  function replaceTextInFile(){
      $filename = "note.txt";
      if (file_exists($filename)) {
          $content = file_get_contents($filename);
          echo "Enter text to find: ";
          $find = readline();
          echo "Enter text to replace with: ";
          $replace = readline();
          $newContent = str_replace($find, $replace, $content);
          file_put_contents($filename, $newContent);
          echo "Text replaced successfully.\n";
      } else {
          echo "File does not exist.\n";
      }
  }

  function handleFilePermissions(){
      echo "Enter filename to check permissions: ";
      $filename = readline();
      if (file_exists($filename)) {
          echo "Readable: " . (is_readable($filename) ? "Yes" : "No") . "\n";
          echo "Writable: " . (is_writable($filename) ? "Yes" : "No") . "\n";
          echo "Executable: " . (is_executable($filename) ? "Yes" : "No") . "\n";
      } else {
          echo "File does not exist.\n";
      }
  }

  function copyFile(){
      echo "Enter source filename: ";
      $source = readline();
      echo "Enter destination filename: ";
      $destination = readline();
      if (file_exists($source)) {
          if (copy($source, $destination)) {
              echo "File copied successfully to {$destination}.\n";
          } else {
              echo "Error copying file.\n";
          }
      } else {
          echo "Source file does not exist.\n";
      }
  }

  function moveFile(){
      echo "Enter source filename: ";
      $source = readline();
      echo "Enter destination filename: ";
      $destination = readline();
      if (file_exists($source)) {
          if (rename($source, $destination)) {
              echo "File moved successfully to {$destination}.\n";
          } else {
              echo "Error moving file.\n";
          }
      } else {
          echo "Source file does not exist.\n";
      }
  }

  function implementFileLocking(){
      $filename = "note.txt";
      $file = fopen($filename, "r+");
      if ($file === false) {
          die("Error: Unable to open the file.");
      }
      if (flock($file, LOCK_EX)) { // Acquire an exclusive lock
          echo "File locked successfully.\n";
          // Perform file operations here
          sleep(5); // Simulate a long operation
          flock($file, LOCK_UN); // Release the lock
          echo "File unlocked successfully.\n";
      } else {
          echo "Error locking file.\n";
      }
      fclose($file);
  }

  function storeAndRetrieveSerializedData(){
      $filename = "data.txt";
      $data = ["name" => "John", "age" => 30, "city" => "New York"];
      $serializedData = serialize($data);
      file_put_contents($filename, $serializedData);
      echo "Data serialized and stored successfully.\n";

      $retrievedData = file_get_contents($filename);
      $unserializedData = unserialize($retrievedData);
      print_r($unserializedData);
  }

  function handleFileUploads(){
      echo "This function requires a web server environment to handle file uploads.\n";
  } 

  function readAndWriteCSVFiles(){
      $filename = "data.csv";
      // Writing to CSV
      $file = fopen($filename, "w");
      fputcsv($file, ["Name", "Age", "City"]);
      fputcsv($file, ["Alice", 28, "Los Angeles"]);
      fputcsv($file, ["Bob", 34, "Chicago"]);
      fclose($file);
      echo "Data written to CSV file successfully.\n";

      // Reading from CSV
      if (($file = fopen($filename, "r")) !== false) {
          while (($data = fgetcsv($file)) !== false) {
              print_r($data);
          }
          fclose($file);
      } else {
          echo "Error opening CSV file.\n";
      }
  }

  function workWithJSONFiles(){
      $filename = "data.json";
      $data = ["name" => "Jane", "age" => 25, "city" => "San Francisco"];
      $jsonData = json_encode($data);
      file_put_contents($filename, $jsonData);
      echo "Data encoded to JSON and stored successfully.\n";

      $retrievedData = file_get_contents($filename);
      $decodedData = json_decode($retrievedData, true);
      print_r($decodedData);
  }

  function storeAndRetrieveAssociativeArrays(){
      $filename = "assoc_data.txt";
      $data = ["fruit" => "Apple", "color" => "Red", "quantity" => 10];
      $serializedData = serialize($data);
      file_put_contents($filename, $serializedData);
      echo "Associative array serialized and stored successfully.\n";

      $retrievedData = file_get_contents($filename);
      $unserializedData = unserialize($retrievedData);
      print_r($unserializedData);
  }

  
