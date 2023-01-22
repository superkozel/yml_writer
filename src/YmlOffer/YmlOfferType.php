<?php

namespace Superkozel\YmlOffer;

//    audiobook — аудиокнига;
//    book — книга;
//    medicine — лекарство;
//    artist.title — музыкальная или видеопродукция;
//    on.demand — товар под заказ.

enum YmlOfferType: string
{
    case ON_DEMAND = 'on.demand';
    case MEDICINE = 'medicine';
    case BOOK = 'book';
    case ARTIST_TITLE = 'artist.title';
    case AUDIO_BOOK = 'audiobook';

}