<?php

/*
  Copyright 2015 Gabor Major

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
*/

namespace Sway\Core;

require_once 'autoload.php';

final class Response {
  // 2xx - Success
  public static function OK($message = null) { return new ResponseDetails(200, 'OK', $message); }

  // 4xx - Client Error
         public static function METHOD_NOT_ALLOWED($message = null) { return new ResponseDetails(452, 'Method not allowed', $message); }
            public static function MODEL_NOT_FOUND($message = null) { return new ResponseDetails(453, 'Model cannot be found', $message); }
  public static function MODEL_CANNOT_BE_POPULATED($message = null) { return new ResponseDetails(454, 'Model could not be populated!', $message); }
         public static function NO_FIELDS_PROVIDED($message = null) { return new ResponseDetails(455, 'No fields provided!', $message); }
         public static function NO_MATCHING_RECORD($message = null) { return new ResponseDetails(456, 'No matching record(s)!', $message); }
                 public static function NO_RESULTS($message = null) { return new ResponseDetails(457, 'No result(s)!', $message); }
       public static function FIELD_DOES_NOT_EXIST($message = null) { return new ResponseDetails(457, 'Field does not exist in model!', $message); }

  // 5xx - Server Error
  public static function INTERNAL_SERVER_ERROR($message = null) { return new ResponseDetails(500, 'Internal Server Error', $message); }
}