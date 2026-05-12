<?php
$config = require __DIR__ . '/config.php';
$business = $config['business'];
$siteUrl = rtrim($config['site']['url'], '/');

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$serviceGroups = [
    'Двигатель и ГРМ' => ['Переборка двигателя', 'Ремонт ГБЦ', 'Замена ГРМ', 'Ремонт системы охлаждения', 'Ремонт топливной системы'],
    'КПП, сцепление, трансмиссия' => ['Ремонт КПП', 'Ремонт сцепления', 'Ремонт карданных валов', 'Ремонт моста'],
    'Ходовая и рулевое' => ['Ремонт ходовой части', 'Ремонт амортизаторов', 'Ремонт рулевых реек', 'Ремонт шаровых опор', 'Восстановление рычагов'],
    'Тормоза и безопасность' => ['Ремонт тормозной системы', 'Диагностика тормозов', 'Замена расходников'],
    'Дополнительные работы' => ['Шиномонтаж', 'Сварочные работы', 'Установка фаркопа', 'Антикор', 'Кузовной ремонт', 'Покраска автомобиля', 'Полировка', 'Удаление катализаторов'],
];

$symptoms = [
    ['Посторонние звуки в ходовой', 'Стук, скрип или вибрация могут быстро добить шаровые, амортизаторы и рычаги.', 'Проверить ходовую'],
    ['Проблемы с коробкой передач', 'Тугое включение, хруст или вылет передачи лучше проверить до отказа КПП.', 'Проверить КПП'],
    ['Перегрев двигателя', 'Перегрев может привести к ремонту ГБЦ или прокладки головки блока.', 'Проверить двигатель'],
    ['Течь масла или антифриза', 'Даже небольшая течь способна оставить машину на маршруте без движения.', 'Найти течь'],
    ['Машина плохо заводится', 'Причина может быть в стартере, генераторе, топливной системе или электрике.', 'Разобраться с запуском'],
    ['Нужна замена ГРМ', 'Своевременная замена снижает риск дорогого ремонта двигателя.', 'Рассчитать ГРМ'],
    ['Проблемы с тормозами', 'Увеличенный ход педали, биение и скрип напрямую влияют на безопасность.', 'Проверить тормоза'],
    ['Нужно плановое обслуживание', 'Масло, фильтры, расходники и осмотр помогают не терять рабочие дни.', 'Записаться на ТО'],
];

$prices = [
    ['Замена ГРМ Газель', 'от 500 ₽'],
    ['Ремонт коробки Газель', 'от 5000 ₽'],
    ['Переборка двигателя Газель', 'от 40000 ₽'],
    ['Ремонт ходовой части Газель', 'от 1000 ₽'],
];

$faq = [
    ['Нужно ли записываться заранее?', 'Лучше позвонить или оставить заявку: мастер уточнит симптомы и подскажет удобное время приезда.'],
    ['Можно ли приехать вечером?', 'Да. Сервис работает ежедневно с 08:00 до 22:00, можно заехать после рейса или рабочего дня.'],
    ['Ремонтируете только Газели?', 'Нет. Работаем с ГАЗ, ГАЗон NEXT, УАЗ, ВАЗ, Лада и другим отечественным, легковым и коммерческим транспортом.'],
    ['Можно ли заказать запчасти через вас?', 'Да. По многим работам мастер подскажет по деталям и поможет оформить заказ через сервис.'],
    ['Сколько стоит ремонт?', 'Точная стоимость зависит от модели, состояния узла и объёма работ. До ремонта согласуем ориентировочную смету.'],
    ['Есть ли гарантия?', 'Да, на выполненные работы предоставляется гарантия. Условия зависят от вида ремонта и установленных деталей.'],
    ['Можно ли оплатить картой?', 'Да. Доступна оплата картой, наличными и банковским переводом.'],
    ['Где находится сервис?', 'Мы находимся в Мытищах: Московская область, проезд № 4530.'],
];

$localBusinessSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'AutoRepair',
    'name' => $business['name'],
    'url' => $siteUrl . '/',
    'image' => $siteUrl . '/assets/img/og-remgazel.webp',
    'telephone' => $business['phone'],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'проезд № 4530',
        'addressLocality' => 'Мытищи',
        'addressRegion' => 'Московская область',
        'addressCountry' => 'RU',
    ],
    'openingHours' => 'Mo-Su 08:00-22:00',
    'areaServed' => ['Мытищи', 'Московская область'],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $business['rating'],
        'ratingCount' => $business['rating_count'],
    ],
    'paymentAccepted' => ['Cash', 'Credit Card', 'Bank Transfer'],
    'makesOffer' => [
        ['@type' => 'Offer', 'name' => 'Ремонт Газели в Мытищах'],
        ['@type' => 'Offer', 'name' => 'Ремонт коммерческого транспорта'],
        ['@type' => 'Offer', 'name' => 'Замена ГРМ Газель'],
        ['@type' => 'Offer', 'name' => 'Ремонт КПП Газель'],
        ['@type' => 'Offer', 'name' => 'Ремонт двигателя Газель'],
        ['@type' => 'Offer', 'name' => 'Ремонт ходовой Газель'],
    ],
];

$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static fn($item) => [
        '@type' => 'Question',
        'name' => $item[0],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item[1],
        ],
    ], $faq),
];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ремонт Газелей в Мытищах | РемГазель</title>
    <meta name="description" content="Ремонт Газелей и коммерческого транспорта в Мытищах: диагностика, КПП, двигатель, ГРМ, ходовая. Ежедневно 08:00–22:00.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($siteUrl) ?>/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Ремонт Газелей и коммерческого транспорта в Мытищах">
    <meta property="og:description" content="Понятная диагностика, согласование стоимости, помощь с запчастями и работа ежедневно до 22:00.">
    <meta property="og:url" content="<?= e($siteUrl) ?>/">
    <meta property="og:image" content="<?= e($siteUrl) ?>/assets/img/og-remgazel.webp">
    <meta property="og:locale" content="ru_RU">
    <meta name="theme-color" content="#1F2937">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='10' fill='%2316A34A'/%3E%3Ctext x='32' y='40' font-size='24' text-anchor='middle' font-family='Arial' font-weight='800' fill='white'%3E%D0%A0%D0%93%3C/text%3E%3C/svg%3E">
    <link rel="preload" href="assets/img/hero-service.webp" as="image">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script type="application/ld+json"><?= json_encode($localBusinessSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <script type="application/ld+json"><?= json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body>
<a class="skip-link" href="#main">Перейти к содержанию</a>

<header class="site-header" data-header>
    <div class="container header-inner">
        <a class="brand" href="#top" aria-label="РемГазель, в начало страницы">
            <span class="brand-mark">РГ</span>
            <span>
                <strong>РемГазель</strong>
                <small>Мытищи, проезд № 4530</small>
            </span>
        </a>
        <nav class="nav" aria-label="Основная навигация" data-nav>
            <a href="#services">Услуги</a>
            <a href="#prices">Цены</a>
            <a href="#process">Как работаем</a>
            <a href="#reviews">Отзывы</a>
            <a href="#contacts">Контакты</a>
        </nav>
        <div class="header-actions">
            <a class="header-phone" href="tel:<?= e($business['phone_href']) ?>"><?= e($business['phone']) ?></a>
            <a class="btn btn-primary" href="#lead">Заявка</a>
            <button class="menu-toggle" type="button" aria-label="Открыть меню" aria-expanded="false" data-menu-toggle>
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<main id="main">
    <section class="hero section" id="top">
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <p class="eyebrow">Автосервис для рабочей машины в Мытищах</p>
                <h1>Ремонт Газелей и коммерческого транспорта в Мытищах</h1>
                <p class="hero-subtitle">Диагностика, ходовая, КПП, двигатель, ГРМ, тормозная система и сервисное обслуживание. Работаем ежедневно с 08:00 до 22:00, поможем с подбором и заказом запчастей.</p>
                <div class="quick-points" aria-label="Короткие преимущества">
                    <span>Специализация на Газелях и ГАЗ</span>
                    <span>Ежедневно до 22:00</span>
                    <span>Запчасти под заказ</span>
                    <span>Расчёт до начала работ</span>
                </div>
                <div class="hero-cta">
                    <a class="btn btn-primary" href="#lead">Рассчитать ремонт</a>
                    <a class="btn btn-secondary" href="tel:<?= e($business['phone_href']) ?>">Позвонить в сервис</a>
                    <a class="btn btn-ghost" target="_blank" rel="noopener" href="<?= e($business['whatsapp']) ?>">Написать в WhatsApp</a>
                </div>
                <div class="hero-facts" aria-label="Контакты сервиса">
                    <div><span>Телефон</span><a href="tel:<?= e($business['phone_href']) ?>"><?= e($business['phone']) ?></a></div>
                    <div><span>Адрес</span><strong><?= e($business['address']) ?></strong></div>
                    <div><span>График</span><strong><?= e($business['hours']) ?></strong></div>
                    <div><span>Рейтинг</span><strong><?= e($business['rating']) ?> по Яндекс Картам</strong><small><?= (int) $business['rating_count'] ?> оценок</small></div>
                </div>
            </div>

            <div class="hero-panel reveal" aria-label="Сервисная панель коммерческого транспорта">
                <div class="panel-top">
                    <div>
                        <span class="panel-label">Статус</span>
                        <strong>Машина на диагностике</strong>
                    </div>
                    <span class="live-pill">Открыто до 22:00</span>
                </div>
                <div class="vehicle-card">
                    <div class="vehicle-shape" aria-hidden="true">
                        <span class="cab"></span>
                        <span class="body"></span>
                        <span class="wheel wheel-a"></span>
                        <span class="wheel wheel-b"></span>
                    </div>
                    <div>
                        <strong>Газель / ГАЗ / ГАЗон NEXT</strong>
                        <span>Осмотр узлов перед сметой</span>
                    </div>
                </div>
                <div class="diagnostic-list">
                    <div><span>Двигатель</span><strong>проверка</strong></div>
                    <div><span>КПП</span><strong>симптомы</strong></div>
                    <div><span>Ходовая</span><strong>осмотр</strong></div>
                    <div><span>Тормоза</span><strong>безопасность</strong></div>
                </div>
                <div class="route-line" aria-label="Маршрут ремонта">
                    <span>Поломка</span>
                    <span>Диагностика</span>
                    <span>Ремонт</span>
                    <span>В работу</span>
                </div>
                <img class="hero-image" src="assets/img/hero-service.webp" width="720" height="420" alt="Инженерная панель диагностики коммерческого транспорта РемГазель">
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">Симптомы</p>
                <h2>Приезжайте, если Газель начала мешать работе</h2>
                <p>Когда машина нужна каждый день, лучше быстро понять причину поломки и не доводить узел до дорогого ремонта.</p>
            </div>
            <div class="symptom-grid">
                <?php foreach ($symptoms as $symptom): ?>
                    <article class="symptom-card reveal">
                        <h3><?= e($symptom[0]) ?></h3>
                        <p><?= e($symptom[1]) ?></p>
                        <a href="#lead"><?= e($symptom[2]) ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">Услуги</p>
                <h2>Ремонтируем основные узлы Газелей, ГАЗ и отечественных авто</h2>
                <p>Собрали работы по понятным категориям, чтобы вы быстро нашли нужное направление.</p>
            </div>
            <div class="service-grid">
                <?php foreach ($serviceGroups as $group => $items): ?>
                    <article class="service-card reveal">
                        <h3><?= e($group) ?></h3>
                        <ul>
                            <?php foreach ($items as $item): ?>
                                <li><?= e($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="inline-cta reveal">
                <div>
                    <strong>Не нашли свою неисправность?</strong>
                    <span>Опишите симптомы, мастер подскажет, с чего начать.</span>
                </div>
                <a class="btn btn-primary" href="#lead">Задать вопрос мастеру</a>
            </div>
        </div>
    </section>

    <section class="section section-graphite" id="prices">
        <div class="container prices-layout">
            <div class="section-head invert reveal">
                <p class="eyebrow">Ориентиры по стоимости</p>
                <h2>Стоимость зависит от состояния машины, поэтому сначала осмотр</h2>
                <p>Перед началом ремонта мастер объяснит проблему и согласует работы. В таблице указаны стартовые ориентиры из карточки сервиса.</p>
                <a class="btn btn-light" href="#lead">Узнать ориентировочную стоимость</a>
            </div>
            <div class="price-table reveal" role="table" aria-label="Ориентиры по стоимости ремонта">
                <?php foreach ($prices as $price): ?>
                    <div class="price-row" role="row">
                        <span role="cell"><?= e($price[0]) ?></span>
                        <strong role="cell"><?= e($price[1]) ?></strong>
                    </div>
                <?php endforeach; ?>
                <p class="price-note">Точная стоимость после осмотра: учитываются модель, состояние узла, объём работ и наличие запчастей.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container why-layout">
            <div class="section-head reveal">
                <p class="eyebrow">Почему обращаются</p>
                <h2>Факты, которые важны владельцу рабочей машины</h2>
                <p>Без громких обещаний: удобный график, понятная коммуникация и помощь с деталями.</p>
            </div>
            <div class="benefit-grid">
                <div class="benefit-card reveal"><strong>Газели и коммерческий транспорт</strong><span>Работают с Газелями, ГАЗ, ГАЗон NEXT и отечественными авто.</span></div>
                <div class="benefit-card reveal"><strong>Ежедневно до 22:00</strong><span>Можно заехать после рейса, смены или рабочего дня.</span></div>
                <div class="benefit-card reveal"><strong>Запчасти через сервис</strong><span>Не всегда нужно самому ездить по нескольким магазинам.</span></div>
                <div class="benefit-card reveal"><strong>Понятные объяснения</strong><span>Сначала расскажут, что случилось, затем согласуют работы.</span></div>
                <div class="benefit-card reveal"><strong>Удобная оплата</strong><span>Карта, наличные и банковский перевод.</span></div>
                <div class="benefit-card reveal"><strong>Комфорт на месте</strong><span>Есть парковка, Wi-Fi, туалет и гарантия на работы.</span></div>
            </div>
        </div>
    </section>

    <section class="section section-muted" id="process">
        <div class="container">
            <div class="section-head reveal">
                <p class="eyebrow">Процесс</p>
                <h2>Как проходит ремонт</h2>
            </div>
            <ol class="process-route reveal">
                <li><span>01</span><strong>Заявка</strong><p>Вы оставляете заявку или звоните.</p></li>
                <li><span>02</span><strong>Симптомы</strong><p>Мастер уточняет проблему и удобное время.</p></li>
                <li><span>03</span><strong>Осмотр</strong><p>Машину проверяют и называют объём работ.</p></li>
                <li><span>04</span><strong>Смета</strong><p>Согласовывают стоимость и запчасти.</p></li>
                <li><span>05</span><strong>Ремонт</strong><p>Выполняют работы по согласованному плану.</p></li>
                <li><span>06</span><strong>В работу</strong><p>Вы забираете машину и получаете рекомендации.</p></li>
            </ol>
        </div>
    </section>

    <section class="section" id="reviews">
        <div class="container reviews-layout">
            <div class="section-head reveal">
                <p class="eyebrow">Отзывы</p>
                <h2>Что отмечают клиенты</h2>
                <p>Рейтинг <?= e($business['rating']) ?> по Яндекс Картам на основе <?= (int) $business['rating_count'] ?> оценок. Это не сотни отзывов, поэтому показываем только конкретные наблюдения клиентов.</p>
            </div>
            <div class="review-grid">
                <figure class="review-card reveal">
                    <blockquote>Мастера объяснили проблему простыми словами и сами помогли с запчастями.</blockquote>
                    <figcaption>Roman Romanov, 2 июня 2024</figcaption>
                </figure>
                <figure class="review-card reveal">
                    <blockquote>В сервисе чисто и опрятно, мастер произвёл хорошее впечатление.</blockquote>
                    <figcaption>Андрей Р., 20 декабря 2025</figcaption>
                </figure>
                <figure class="review-card reveal">
                    <blockquote>Отзывчиво помогли, когда машина сломалась неподалёку.</blockquote>
                    <figcaption>Anait, 21 декабря 2025</figcaption>
                </figure>
            </div>
            <p class="source-note">Отзывы взяты из открытых данных Яндекс Карт.</p>
        </div>
    </section>

    <section class="section commercial-section">
        <div class="container commercial-grid">
            <div class="commercial-copy reveal">
                <p class="eyebrow">Для коммерческого транспорта</p>
                <h2>Для Газели простой — это потерянные заказы</h2>
                <p>Если машина работает каждый день, важно быстро понять причину поломки и не затягивать ремонт. В «РемГазель» можно обратиться по ходовой, двигателю, КПП, тормозам, ГРМ, сцеплению и другим узлам коммерческого транспорта.</p>
                <div class="risk-card">
                    <strong>Перед ремонтом согласуем работы и ориентировочную стоимость.</strong>
                    <span>Так проще планировать время, детали и бюджет без лишнего хаоса.</span>
                </div>
            </div>
            <form class="lead-form mini-form reveal" data-lead-form novalidate>
                <h3>Быстрая заявка мастеру</h3>
                <label>Марка авто<input name="car" type="text" maxlength="80" placeholder="Например, Газель Next"></label>
                <label>Что случилось<textarea name="issue" maxlength="1000" placeholder="Коротко опишите симптомы"></textarea></label>
                <label>Машина на ходу?
                    <select name="vehicle_status">
                        <option value="Да">Да</option>
                        <option value="Нет">Нет</option>
                    </select>
                </label>
                <label>Когда удобно приехать<input name="preferred_time" type="text" maxlength="80" placeholder="Сегодня вечером, завтра утром"></label>
                <label>Телефон *<input name="phone" type="tel" inputmode="tel" autocomplete="tel" required placeholder="+7 900 000-00-00"></label>
                <input type="hidden" name="contact_method" value="Звонок">
                <input type="hidden" name="page_url" value="">
                <input type="hidden" name="utm_source" value="">
                <input type="hidden" name="utm_medium" value="">
                <input type="hidden" name="utm_campaign" value="">
                <label class="hp-field">Сайт<input name="website" type="text" tabindex="-1" autocomplete="off"></label>
                <button class="btn btn-primary" type="submit">Отправить заявку мастеру</button>
                <p class="form-message" role="status" aria-live="polite"></p>
            </form>
        </div>
    </section>

    <section class="section section-muted" id="faq">
        <div class="container faq-layout">
            <div class="section-head reveal">
                <p class="eyebrow">FAQ</p>
                <h2>Коротко о частых вопросах</h2>
            </div>
            <div class="faq-list reveal">
                <?php foreach ($faq as $item): ?>
                    <details>
                        <summary><?= e($item[0]) ?></summary>
                        <p><?= e($item[1]) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section final-cta" id="lead">
        <div class="container lead-grid">
            <div class="section-head invert reveal">
                <p class="eyebrow">Заявка</p>
                <h2>Опишите проблему — подскажем, с чего начать</h2>
                <p>Оставьте телефон и пару слов о машине. Мастер свяжется, уточнит симптомы и подскажет ближайший шаг.</p>
                <div class="contact-stack">
                    <a href="tel:<?= e($business['phone_href']) ?>"><?= e($business['phone']) ?></a>
                    <span><?= e($business['address']) ?></span>
                    <span><?= e($business['hours']) ?></span>
                </div>
                <div class="hero-cta">
                    <a class="btn btn-light" target="_blank" rel="noopener" href="https://yandex.ru/maps/?text=<?= urlencode($business['address']) ?>">Построить маршрут</a>
                    <a class="btn btn-outline-light" target="_blank" rel="noopener" href="<?= e($business['whatsapp']) ?>">WhatsApp</a>
                </div>
            </div>
            <form class="lead-form reveal" data-lead-form novalidate>
                <label>Имя<input name="name" type="text" maxlength="80" autocomplete="name" placeholder="Как к вам обращаться"></label>
                <label>Телефон *<input name="phone" type="tel" inputmode="tel" autocomplete="tel" required placeholder="+7 900 000-00-00"></label>
                <label>Марка авто<input name="car" type="text" maxlength="80" placeholder="Газель, ГАЗон NEXT, УАЗ, Лада"></label>
                <label>Что нужно сделать / что сломалось<textarea name="issue" maxlength="1000" placeholder="Например: стук в ходовой, перегрев, плохо включается передача"></textarea></label>
                <label>Удобный способ связи
                    <select name="contact_method">
                        <option value="Звонок">Звонок</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Telegram">Telegram</option>
                    </select>
                </label>
                <label class="consent"><input type="checkbox" name="consent" value="1" required> Согласен на обработку персональных данных для связи по заявке</label>
                <input type="hidden" name="page_url" value="">
                <input type="hidden" name="utm_source" value="">
                <input type="hidden" name="utm_medium" value="">
                <input type="hidden" name="utm_campaign" value="">
                <label class="hp-field">Сайт<input name="website" type="text" tabindex="-1" autocomplete="off"></label>
                <button class="btn btn-primary" type="submit">Получить консультацию</button>
                <p class="form-message" role="status" aria-live="polite"></p>
            </form>
        </div>
    </section>

    <section class="section contacts-section" id="contacts">
        <div class="container contacts-grid">
            <div class="contact-card reveal">
                <p class="eyebrow">Контакты</p>
                <h2>РемГазель</h2>
                <dl>
                    <div><dt>Адрес</dt><dd><?= e($business['address']) ?></dd></div>
                    <div><dt>Телефон</dt><dd><a href="tel:<?= e($business['phone_href']) ?>"><?= e($business['phone']) ?></a></dd></div>
                    <div><dt>График</dt><dd><?= e($business['hours']) ?></dd></div>
                    <div><dt>Оплата</dt><dd>Карта, наличные, банковский перевод</dd></div>
                    <div><dt>На месте</dt><dd>Парковка, Wi‑Fi, туалет</dd></div>
                </dl>
                <div class="hero-cta">
                    <a class="btn btn-primary" target="_blank" rel="noopener" href="https://yandex.ru/maps/?text=<?= urlencode($business['address']) ?>">Построить маршрут</a>
                    <a class="btn btn-secondary" href="tel:<?= e($business['phone_href']) ?>">Позвонить</a>
                </div>
            </div>
            <div class="map-placeholder reveal" role="img" aria-label="Карта проезда к автосервису РемГазель в Мытищах">
                <div class="map-grid" aria-hidden="true"></div>
                <div class="map-pin">
                    <strong>РемГазель</strong>
                    <span><?= e($business['address']) ?></span>
                    <a target="_blank" rel="noopener" href="https://yandex.ru/maps/?text=<?= urlencode($business['address']) ?>">Открыть в Яндекс Картах</a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <span>© <?= date('Y') ?> РемГазель</span>
        <span>Ремонт Газели, ГАЗ и коммерческого транспорта в Мытищах</span>
    </div>
</footer>

<div class="mobile-cta" aria-label="Быстрые действия">
    <a href="tel:<?= e($business['phone_href']) ?>">Позвонить</a>
    <a target="_blank" rel="noopener" href="<?= e($business['whatsapp']) ?>">WhatsApp</a>
    <a href="#lead">Заявка</a>
</div>

<script src="assets/js/main.js" defer></script>
</body>
</html>
