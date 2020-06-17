<?php

return [
    'validation' => [ //request.validation
        '_required' => 'Το πεδίο είναι απαραίτητο!', //request.validation._required
        '_price' => 'Το πεδίο πρέπει να είναι αριθμός με το πολύ 2 δεκαδικά', //request.validation._price
        '_phone' => 'Το πεδίο μπορεί να περιέχει μόνο αριθμούς και κενά.', //request.validation._phone
        '_uniqueEmail' => 'Το email αυτό χρησιμοποιείται ήδη!', //request.validation._uniqueEmail
        '_present' => 'Το πεδίο πρέπει να είναι συμπληρωμένο.', //request.validation._present 
        '_email' => 'Το πεδίο πρέπει να είναι μία έγκυρη διεύθυνση email.', //request.validation._email
        '_url' => 'Το πεδίο πρέπει να είναι έγκυρη διεύθυνση URL.', //request.validation._url
        '_image' => 'Το πεδίο πρέπει να είναι εικόνα.(jpg,png)', //request.validation._image
        '_mimes' => 'Το πεδίο πρέπει να είναι αρχείο τύπου: :values.', //request.validation._mimes
        '_between' => 'Το πεδίο πρέπει να είναι μεταξύ :min - :max.', //request.validation._between
        '_integer' => 'Το πεδίο πρέπει να είναι ακέραιος.', //request.validation._integer
        '_numeric' => 'Το πεδίο πρέπει να είναι αριθμός.', //request.validation._numeric
        '_min' => 'Το πεδίο πρέπει να είναι τουλάχιστον :min.', //request.validation._min
        '_max' => 'Το πεδίο δεν μπορεί να είναι μεγαλύτερο από :max.', //request.validation._max
        '_maxFileSize_5MB' => 'Το αρχείο δεν μπορεί να είναι μεγαλύτερο από 5MB.', //request.validation._maxFileSize_5MB
        '_same' => 'Το πεδίο :attribute πρέπει να είναι ίδιο με το :other.', //request.validation._same
        ],
];