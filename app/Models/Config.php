<?php

namespace App\Models;

class Config extends BaseModel
{
    const NEXT_PAGE_SURVEY_QUESTION = 1;
    const QUESTION_TEXT = 1;
    const QUESTION_TEXTAREA = 2;
    const QUESTION_SELECT = 3;
    const QUESTION_RADIO = 4;
    const QUESTION_CHECKBOX = 5;
}
