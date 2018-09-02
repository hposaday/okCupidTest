# okCupidTest
okCupid challenge

The script is pretty heavy i was able to run it locally on my machine with an intel i5 and 6GB of Ram, it takes a few seconds to process.

the script was written in PHP, we can use an apache server to run it. 

We can install XAMPP (avaliable for Windows, Linux and Mac OS) for a quick and easy setup of the local server i`ll put the link below:

https://www.apachefriends.org/download.html

after the installation we need to start apache in xampp control panel, and put the project in xampp/htdocs folder

then we just simply go to localhost/okCupidTest/server/app.php on the browser, this will execute the script and show the output on screen.


With this algorithm we calculate the match percentage of two users based on their answers to a set of questions, first we have the data of each user with their responses, this data have the following structure:

"profiles": [
    {
      "id": 0,
      "answers": [
        {
          "questionId": 275,
          "answer": 2,
          "acceptableAnswers": [
            0
          ],
          "importance": 1
        },
        {
          "questionId": 3,
          "answer": 1,
          "acceptableAnswers": [
            1,
            3
          ],
          "importance": 1
        }
        
    and so on...
    
 with this data we calculate an score for the sets of questions that both answered.
 
 after that we calculate the percentage of satisfaction for both users, this percentage means how much the two users could be satisfied with each other. the output data of the scrpit looks like this:
 
 [profileId] => 0
            [matches] => Array
                (
                    [0] => Array
                        (
                            [profileId] => 1
                            [score] => 0.17
                        )

                    [1] => Array
                        (
                            [profileId] => 2
                            [score] => 0.61
                        )

                    [2] => Array
                        (
                            [profileId] => 3
                            [score] => 0.58
                            
                            and so on...
                            

the script need to be optimized to reduce the time of data processing, these could be done in future revisions.

thanks for using it.



   
   
    


