<?php
/* ==========================================================
   SITE CONFIG — edit this file first.
   Everything under 'public' is also passed to the JS as window.SITE.
   ========================================================== */
return [
  'public' => [
    'studioName' => '12 Bit Stories',
    'tagline'    => 'CINEMATIC PHOTOGRAPHY STUDIO',
    'email'      => 'hello@yourstudio.com',
    'phone'      => '+91 00000 00000',
    'city'       => 'Ahmedabad, India',
    'social'     => ['instagram' => '#', 'behance' => '#', 'linkedin' => '#'],

    /* Watermark system
       enabled : true / false  — global on/off
       mode    : 'text' | 'logo'
       logo    : SVG or transparent PNG (used when mode = 'logo')
       Colour / size / opacity / position are CSS variables in style.css (:root --wm-*) */
    'watermark' => [
      'enabled' => true,
      'mode'    => 'text',
      'logo'    => 'assets/brand/logo.svg',
    ],

    /* Hero scroll sequence (frames extracted from the reference video) */
    'hero' => [
      'desktop' => ['path' => 'assets/frames/hero/desktop/frame_', 'count' => 240],
      'mobile'  => ['path' => 'assets/frames/hero/mobile/frame_',  'count' => 120],
      'ext'     => '.webp',
      'pad'     => 4,
      'poster'  => 'assets/images/hero-poster.webp',
      'mobileBreakpoint' => 768,
    ],

    /* Contact form posts here (handled by contact.php itself) */
    'form' => ['endpoint' => 'contact.php'],
  ],

  /* Server-only settings (never sent to the browser) */
  'mail' => [
    'to'        => 'hello@yourstudio.com',          // where enquiries are delivered
    'from'      => 'no-reply@yourstudio.com',       // must be a domain your server may send for
    'subject'   => 'New enquiry — 12 Bit Stories',
  ],
  'timezone' => 'Asia/Kolkata',

  /* Every valid enquiry is also appended here (blocked from the web by storage/.htaccess) */
  'enquiry_log' => __DIR__ . '/../storage/enquiries.csv',
];
