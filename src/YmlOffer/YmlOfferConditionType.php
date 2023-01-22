<?php

namespace Superkozel\YmlOffer;

//preowned — бывший в употреблении, раньше принадлежал другому человеку.
//showcasesample — витринный образец.
//reduction — уцененный товар.
//refurbished — указывается только для одежды и аксессуаров.

enum YmlOfferConditionType: string
{
    case PREOWNED = 'preowned';
    case SHOWCASE_SAMPLE = 'showcasesample';
    case REDUCTION = 'reduction';
    case REFURBISHED = 'refurbished';
}