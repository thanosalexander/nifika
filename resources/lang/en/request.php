<?php

return [
    'validation' => [ //request.validation
        '_required' => 'The field is required.', //request.validation._required
        '_price' => 'The field must be a number with a maximum of 2 decimal places.', //request.validation._price
        '_phone' => 'The field can only contain numbers and spaces.', //request.validation._phone
        '_uniqueEmail' => 'This email is already in use!', //request.validation._uniqueEmail
        '_present' => 'The field must be filled in.', //request.validation._present 
        '_email' => 'The field must be a valid email address.', //request.validation._email
        '_url' => 'The field must be a valid url address.', //request.validation._url
        '_image' => 'The field must be an image. (jpg, png)', //request.validation._image
        '_mimes' => 'The field must be a file of type: :values.', //request.validation._mimes
        '_between' => 'The field must be between :min and :max.', //request.validation._between
        '_integer' => 'The field must be an integer.', //request.validation._integer
        '_numeric' => 'The field must be a number.', //request.validation._numeric
        '_min' => 'The field must be at least :min.', //request.validation._min
        '_max' => 'The field may not be greater than :max.', //request.validation._max
        '_maxFileSize_5MB' => 'The file may not be greater than 5MB.', //request.validation._maxFileSize_5MB
        '_same' => 'The :attribute and :other must match.', //request.validation._same
        ],
];