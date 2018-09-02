<?php 
    //function to print data for debuggin purposes
    function prettyPrint($arrayToPrint){
        echo "<pre style='background-color:yellow;'>".print_r($arrayToPrint,true)."</pre>";
    }

    //we get the users data and store it 
    $userDataInput = file_get_contents('../stdin/input.json');

    // we decode de JSON data 
    $decodedUserDataInput = json_decode($userDataInput);
    
    //we set the values of levels of importance for the questions
    $levelsOfImportance =[
         0,
         1,
         10,
         50,
         250
        ];
    
    // we initialize arrays of the data we need
    $GLOBALS['userData'] = $decodedUserDataInput;
    $GLOBALS['levelsOfImportance'] = $levelsOfImportance;
    $GLOBALS['resultData'] = [];
    $GLOBALS['finalData'] = [];
    
    // function to calculate the score of questions, we get the score of each set of questions relative to the other users 
    
    function calculateMatchs(){
        foreach($GLOBALS['userData']->profiles as $profileKey => $profileData ){
            $matchesScores = calculateMatchsForProfile($profileData->answers,$profileData->id);
            $dataToPush = [
            
                "profileId"=> $profileData->id,
                "matches"=> $matchesScores
            
        ];
            resultPusher($dataToPush);
        }
        
    }

    //function to compare the set of answers of a given user with each user of the data input
    function calculateMatchsForProfile($answers,$profileId){
        $matchesScores = [];
        foreach($GLOBALS['userData']->profiles as $matchProfileKey => $matchProfileData){
            //conditional to avoid self-comparison, in other words not to compare two profiles with same Id
            if($profileId != $matchProfileData->id){
                $matchPercentage =compareQuestions($answers,$matchProfileData->answers);
                $dataToPush = [
                    
                    "profileId"=> $matchProfileData->id,
                    "score"=> $matchPercentage
                
            ];

            array_push($matchesScores,$dataToPush);

            }
        }
        return $matchesScores;
    }

    //compare each answer of the user with another user (i like to call it match) to get an score 
    //based on the acceptable answer of the first user   
    function compareQuestions($profileAnswers,$matchAnswers){

        $totalPossiblePoints =0;
        $matchScore =0; 
        
        foreach($profileAnswers as $profileAnswerKey => $profileAnswerData){
            foreach($matchAnswers as $matchAnswerKey => $matchAnswerData){
                if($profileAnswerData->questionId == $matchAnswerData->questionId){
                    //we calculate and store the total score of a question Set
                    $totalPossiblePoints += $GLOBALS['levelsOfImportance'][$profileAnswerData->importance];
                    foreach($profileAnswerData->acceptableAnswers as $acceptableAnswerKey => $acceptableAnswerData){
        
                        if($acceptableAnswerData == $matchAnswerData->answer){
                            //we calculate the score of the match to a given set of questions
                            $matchScore += $GLOBALS['levelsOfImportance'][$profileAnswerData->importance];
                            break;   
                        }
                    }
                    
                    
                }
            }   
        }
        // the score the matchs gets
        $matchPercentage = round((100* $matchScore)/$totalPossiblePoints)/100;

        return $matchPercentage;
         
    }
    //this function add results to the main resultData array, this array have the score of the questions Sets for each user
    /* 
    [profileId] => 0
    [matches] => Array
        (
            [0] => Array
                (
                    [profileId] => 1
                    [score] => 0.17
                )
            and so on...
    */
    function resultPusher($dataToPush){
        array_push($GLOBALS['resultData'],$dataToPush);
    }

    //function to calculate the match score for a pair of user
    // sqrt(firstProfileScore * secondProfileScore)
    function calculatePairMatchsPerecentage(){
        $profileId =0;
        
        $matchProfileId = 0;
        $finalScore=0;

        foreach($GLOBALS['resultData'] as $profileKey => $profileData ){
            $profileId = $profileData['profileId'];
            $matches = [];
            foreach($profileData['matches'] as $profileMatchsKey => $profileMatchsData){
                $matchProfileId = $profileMatchsData['profileId'];
                foreach($GLOBALS['resultData'] as $matchKey => $matchData){   
                    //we search the score of the user on the user match    
                    if($matchData['profileId'] == $profileMatchsData['profileId']){
                            foreach($matchData['matches'] as $matchMatchsKey => $matchMatchsData){
                                if($profileData['profileId']== $matchMatchsData['profileId']){
                                   // we calculate de match percentage for both users
                                    $finalScore = round(sqrt($matchMatchsData['score'] *$profileMatchsData['score'] ),2);
                                }
                            }
                        }
                }
                 $dataToPush = [
                    
                    "profileId"=>$matchProfileId,
                    "score"=> $finalScore
                
                ];
                array_push($matches,$dataToPush);
            }

            $dataToPush = [
            
                "profileId"=> $profileId,
                "matches"=> $matches
            
            ];

            array_push($GLOBALS['finalData'],$dataToPush);
            
        }
       
    }
        
    
    calculateMatchs();
    calculatePairMatchsPerecentage();
    prettyPrint($GLOBALS['finalData']);
    prettyPrint("done");
    
    
    


?>