<?php

namespace App\Dto;

enum ListingStateEnum: string
{
    case active = 'active';
    case inactive = 'inactive';
    case sold_out = 'sold_out';
    case draft = 'draft';
    case expired = 'expired';
}
