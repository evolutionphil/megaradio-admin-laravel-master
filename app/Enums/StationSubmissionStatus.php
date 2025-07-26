<?php

namespace App\Enums;

enum StationSubmissionStatus:string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Denied = 'denied';
}
