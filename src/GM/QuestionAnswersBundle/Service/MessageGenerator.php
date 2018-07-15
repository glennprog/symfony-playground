<?php

namespace GM\QuestionAnswersBundle\Service;

class MessageGenerator
{
    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function Msg_InsertionDB_OK(){
        $messages = [
            'Sucessfull insertion in database',
            'Correct insertion in database',
            'Insertion query OK',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function Msg_UpdateDB_OK(){
        $messages = [
            'Sucessfull update in database',
            'Correct update in database',
            'Update query OK',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function Msg_DeleteDB_OK(){
        $messages = [
            'Sucessfull delete in database',
            'Correct delete in database',
            'Delete query OK',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

}