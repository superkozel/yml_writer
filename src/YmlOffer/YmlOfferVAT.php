<?php

namespace Superkozel\YmlOffer;


//НДС не облагается — 6 или NO_VAT
//0 % — 5 или VAT_0
//10 % — 2 или VAT_10
//20 % — 7 или VAT_20

enum YmlOfferVAT
{
    case NO_VAT;
    case VAT_0;
    case VAT_10 ;
    case VAT_20;
}