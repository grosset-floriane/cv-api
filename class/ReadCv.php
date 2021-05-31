<?php

require_once '../../../privat/AccessData.php';

class ReadCv extends AccessData
{ 
    
    private $host;
    private $username;
    private $password;
    private $database; 

    // CV Table
    private $cvGeneralTable = "cv";
    private $cvUserDataTable = "cv_personalinfo";
    private $cvCategoriesTable = "cv_categories";
    private $cvContentTable = "cv_content";

    // Conncet to database
    private $dbConnect = false;
    public function __construct() {
        if (!$this->dbConnect) {
            $this->host = $this->getHost();
            $this->database = $this->getDatabase();
            $this->username = $this->getUsername();
            $this->password = $this->getPassword();

            $conn = new mysqli($this->host, 
                                $this->username, 
                                $this->password, 
                                $this->database);
            if ($conn->connect_error) {
                die("Error failed to connect to Database: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }

        }
    }

    public function getFullCvData($cvId) {
        $queryCvGeneral = "";

        if (!is_int($cvId) || $cvId <= 0) {
            $cvId = 1;
        } 

        $cvGeneral = $this->getCvGeneral($cvId);
        

        if( !empty($cvGeneral) ) {
            $cvUserData = $this->getUserData($cvId);

            $cvCategories = $cvGeneral["cvCategories"];
            $cvContent = $this->getCvContent($cvId, $cvCategories);

            // $cvContent = "test2";

            $cvData = ['cvGeneral' => $cvGeneral, 'cvUserData' => $cvUserData, 'cvContent' => $cvContent];

            
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            header('Content-Type: application/json');
		
		    echo json_encode($cvData);

        } else {
            echo "No cv found";
            var_dump($cvGeneral);
        }

        

        

    }

    private function getCvGeneral($cvId) {
        $queryCvGeneral = "SELECT * 
                             FROM " . $this->cvGeneralTable . " 
                             WHERE cv_id = '$cvId'
                             ORDER BY cv_id DESC
                             LIMIT 1";

        $resultCvGeneral = $this->dbConnect->query($queryCvGeneral);

        $cvGeneral = [];

        if ($resultCvGeneral->num_rows === 1) {
            while ($cvData = $resultCvGeneral->fetch_assoc()) {
                $cvGeneral = [
                    "cvName" => $cvData['cv_name'],
                    "cvCategories" => $cvData['cv_categories']
                ];
            }
        }
        
        return $cvGeneral;
    }


    private function getUserData($cvId) {
        $queryUserData = "SELECT * 
                            FROM " . $this->cvUserDataTable . "
                            WHERE cv_id = '$cvId' "; 
        
        $resultUserData = $this->dbConnect->query($queryUserData);

        $cvUserData = [];

        if ($resultUserData->num_rows === 1) {
            while ($userData = $resultUserData->fetch_assoc()) {
                $cvUserData = [
                    'name' => $userData['my_name'],
                    'tagline' => $userData['tagline'],
                    'address' => $userData['address'],
                    'phone_number' => $userData['phone_number'],
                    'email' => $userData['email'],
                    'website' => $userData['website']
                ];
            }
        }

        return $cvUserData;

    }

    private function getCvContent($cvId, $cvCategories) {
        $cvCategories = explode(",",$cvCategories);

        $cvContent = [];
        foreach ($cvCategories as $catId) {
            $categoryTaxonomy = $this->getCategoryTaxonomy($catId);
            $contentOfCategory = $this->getContentOfCategory($cvId, $catId);

            array_push($cvContent, [$categoryTaxonomy, $contentOfCategory]);
         
        }

        return $cvContent;
    }

    private function getCategoryTaxonomy($catId) {
        $queryTaxonomy = "SELECT * FROM " . $this->cvCategoriesTable . "
                                WHERE category_id = '$catId'";
        
        $resultCategory = $this->dbConnect->query($queryTaxonomy);

        if($resultCategory->num_rows === 1) {
            return $resultCategory->fetch_assoc();   
        }

    }


    private function getContentOfCategory($cvId, $catId) {
        $queryContentOfCategory = "SELECT * FROM " . $this->cvContentTable . "
                                        WHERE category = '$catId'
                                          AND cv_id = '$cvId'
                                                ORDER BY start_date DESC";
        
        $resultContentOfCategory = $this->dbConnect->query($queryContentOfCategory);

        if ($resultContentOfCategory->num_rows >= 1) {
            $contentOfCat = [];
            while ($contentItem = $resultContentOfCategory->fetch_assoc()) {
                
                $contentItemData = [
                    'title' => $contentItem['title'],
                    'subtitle' => $contentItem['subtitle'],
                    'description' => $contentItem['description'],
                    'text' => $contentItem['text'],
                    'start_date' => $contentItem['start_date'],
                    'end_date' => $contentItem['end_date'],
                    'link_to_post' => $contentItem['link_to_post'],
                    'image' => $contentItem['image']
                ];

                array_push($contentOfCat, $contentItemData);
            }
            return $contentOfCat;
        }
    }


}