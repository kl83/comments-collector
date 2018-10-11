<?php

return [
    'sources' => [
        [
            'class' => 'app\sources\YandexSource',
            'url' => 'https://yandex.ru/maps/org/butik_otel_truvor/199840377006/',
        ],
        [
            'class' => 'app\sources\GoogleSource',
            'url' => 'https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyCneFBjpdlhSROSJxWQjfq78E-OJnRgTZo&placeid=ChIJEUHazzjqvkYRUW-9A0hYky0',
        ],
        [
            'class' => 'app\sources\TripadvisorSource',
            'url' => 'https://www.tripadvisor.ru/Hotel_Review-g298502-d12960058-Reviews-Boutique_Hotel_Truvor-Veliky_Novgorod_Novgorod_Oblast_Northwestern_District.html',
        ],
        [
            'class' => 'app\sources\BookingSource',
            'url' => 'https://www.booking.com/reviews/ru/hotel/truvor.ru.html?aid=304142;label=gen173nr-1FCAEoggJCAlhYSDNYBGjCAYgBAZgBIcIBCndpbmRvd3MgMTDIAQzYAQHoAQH4AQuSAgF5qAID;sid=5a04c83a41f0d80f82a6592bca2a90c1',
        ],
    ],
    'storage' => [
        'class' => 'app\storages\WordpressStorage',
        'postType' => 'review',
        'wpPath' => '/home/user/web/truvorhotel/public_html',
    ],
];
