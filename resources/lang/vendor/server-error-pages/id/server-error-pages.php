<?php
//|----------------------------------------------------
//| link_to option can be only 'home', 'reload' or 'previous'
//|----------------------------------------------------
return [
    '400' => [
        'title' => "400 Bad Request",
        'description' => "Sorry! The <em>:domain</em> tidak mengerti dengan alamat diatas.... hmm?.",
        'icon' => "fa fa-ban red",
        'button' => [
            'name' => "Kembali Kehalaman Sebelumnya",
            'link_to' => "previous",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "error 400 hmmmmm... Itu karena Saya tidak mengerti syntax yang diberikan."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "Jika Anda Pengunjung",
                'description' => "Periksa apakah pada saat pengisian data, Anda mengisinya dengan Benar."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "OM Telolet OM."
            ],
        ],
    ],
    '401' => [
        'title' => "401 Unauthorized",
        'description' => "Oops! Anda tidak diijinkan lewat sini <em>:domain</em>.",
        'icon' => "fa fa-lock red",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 401 error status indicates that the request has not been applied because it lacks valid authentication credentials for the target resource."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "Refresh this page or try do login again. If you need immediate assistance, please send us an email instead."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Refresh this page or try do login again. If the error persists get in touch with your website provider if you believe this to be an error."
            ],
        ],
    ],
    '403' => [
        'title' => "403 Forbidden",
        'description' => "Maaf. ANda tidak diijinkan untuk mengakses halaman  <em>:domain</em>.",
        'icon' => "fa fa-ban red",
        'button' => [
            'name' => "Kembali ke Beranda",
            'link_to' => "home",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "Hanya yang berkepentingan yang boleh.... Maaf ya."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "Jika Anda Pengunjung",
                'description' => "Lapor mimin, tapi siap siap dimarah."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Oi min min kerja min."
            ],
        ],
    ],
    '404' => [
        'title' => "404 Not Found",
        'description' => "Errrrr... kami tidak menemukan yang anda minta, Benar ini alamatya ? <em>:domain</em>.",
        'icon' => "fa fa-frown-o red",
        'button' => [
            'name' => "Kembali Ke Beranda",
            'link_to' => "home",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "Mungkin halaman ini sudah dihapus atau memang tidak ada di dunia persilatan."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "Pengunjung ?",
                'description' => "Coba back dulu."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Min tolong cek min."
            ],
        ],
    ],
    '405' => [
        'title' => "405 Method not allowed",
        'description' => "You requested this page with an invalid or nonexistent HTTP method on <em>:domain</em>.",
        'icon' => "fa fa-ban orange",
        'button' => [
            'name' => "Back to previous page",
            'link_to' => "previous",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 405 error status indicates that the request method is known by the server but has been disabled and cannot be used."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "Go to previous page and retry. If you need immediate assistance, please send us an email instead."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Go to previous page and retry. If the error persists get in touch with your website provider if you believe this to be an error."
            ],
        ],
    ],
    '419' => [
        'title' => '419 Authentication Timeout',
        'description' => "The page has expired due to inactivity <em>:domain</em>.",
        'icon' => "fa fa-lock red",
        'button' => [
            'name' => "Kembali ke beranda",
            'link_to' => "home",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "Anda kelamaan melamun...."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "Login Lagi."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Refresh this page or try do login again. If the error persists get in touch with your website provider if you believe this to be an error."
            ],
        ],
    ],
    '429' => [
        'title' => '429 Too Many Requests',
        'description' => "The web server is returning a rate limiting notification <em>:domain</em>.",
        'icon' => "fa fa-dashboard red",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "This error means you have exceeded the request rate limit for the the web server you are accessing.</p><p class=\"lead\">Rate Limit Thresholds are set higher than a human browsing this site should be able to reach and mostly for protection against automated requests and attacks."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "The best thing to do is to slow down with your requests and try again in a few minutes. We apologize for any inconvenience."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "This error is mostly likely very brief, the best thing to do is to check back in a few minutes and everything will probably be working normal again. If the error persists, contact your website host."
            ],
        ],
    ],
    '500' => [
        'title' => "500 Internal Server Error",
        'description' => "The web server is returning an internal error for <em>:domain</em>.",
        'icon' => "glyphicon glyphicon-fire red",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 500 error status implies there is a problem with the web server's software causing it to malfunction."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "Nothing you can do at the moment. If you need immediate assistance, please send us an email instead. We apologize for any inconvenience."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "This error can only be fixed by server admins, please contact your website provider."
            ],
        ],
    ],
    '502' => [
        'title' => "502 Bad Gateway",
        'description' => "The web server is returning an unexpected networking error for <em>:domain</em>.",
        'icon' => "fa fa-bolt orange",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 502 error status implies that that the server received an invalid response from an upstream server it accessed to fulfill the request."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "<a href=\"http://isup.me/:domain\" target=\"_blank\" rel=\"nofollow\">Check to see if this website down for everyone or just you.</a>"
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Clearing your browser cache and refreshing the page may clear this issue. If the problem persists and you need immediate assistance, please contact your website provider."
            ],
        ],
    ],
    '503' => [
        'title' => "503 Service Unavailable",
        'description' => "The web server is returning an unexpected temporary error for <em>:domain</em>.",
        'icon' => "fa fa-exclamation-triangle orange",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 503 error status implies that this is a temporary condition due to a temporary overloading or maintenance of the server. This error is normally a brief temporary interruption."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "If you need immediate assistance, please send us an email instead. We apologize for any inconvenience."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "This error is mostly likely very brief, the best thing to do is to check back in a few minutes and everything will probably be working normal again."
            ],
        ],
    ],
    '504' => [
        'title' => "504 Gateway Timeout",
        'description' => "The web server is returning an unexpected networking error for <em>:domain</em>.",
        'icon' => "fa fa-clock-o orange",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "A 504 error status implies there is a slow IP communication problem between back-end servers attempting to fulfill this request."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "<a href=\"http://isup.me/:domain\" target=\"_blank\" rel=\"nofollow\">Check to see if this website down for everyone or just you.</a></p><p>Also, clearing your browser cache and refreshing the page may clear this issue. If the problem persists and you need immediate assistance, please send us an email instead."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "Clearing your browser cache and refreshing the page may clear this issue. If the problem persists and you need immediate assistance, please contact your website provider."
            ],
        ],
    ],
    'maintenance' => [
        'title' => "Temporary Maintenance",
        'description' => "The web server for <em>:domain</em> is currently undergoing some maintenance.",
        'icon' => "fa fa-cogs green",
        'button' => [
            'name' => "Try This Page Again",
            'link_to' => "reload",
        ],
        'why' => [
            'title' => "What happened?",
            'description' => "Servers and websites need regular maintenance just like a car to keep them up and running smoothly."
        ],
        'what_do' => [
            'title' => "What can I do?",
            'visitor' => [
                'title' => "If you're a site visitor",
                'description' => "If you need immediate assistance, please send us an email instead. We apologize for any inconvenience."
            ],
            'owner' => [
                'title' => "If you're the site owner",
                'description' => "The maintenance period will mostly likely be very brief, the best thing to do is to check back in a few minutes and everything will probably be working normal again."
            ],
        ],
    ],
];
