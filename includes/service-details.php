<?php
/* ==========================================================
   SERVICE DETAIL PAGES — service.php?id=<slug>
   Keyed by the 'slug' of each entry in $DATA['services'] (includes/data.php).

   hero     : optional image for the top of the page (no image → text-only header)
   pos      : optional object-position for the hero crop
   photos   : gallery — a photo category from $DATA['photos'] ('Wedding', 'Pre-Wedding', 'Studio'),
              an array of image paths, or null when there is no matching work yet
   meta     : four short facts shown in a row
   faq      : [question, answer] pairs
   shots    : [title, line] pairs — "Shot list" section, shown only while the page has no gallery.
              A page with no 'hero' gets the animated viewfinder header; add 'hero' / 'photos'
              when you have real work and the page switches to the photo layout automatically.
   ========================================================== */
$W = 'assets/images/gallery/wedding/';
$P = 'assets/images/gallery/pre-wedding/';
$S = 'assets/images/gallery/studio/';

return [

  /* ---------------- People & Weddings ---------------- */
  'wedding-photography' => [
    'tagline'  => 'Your wedding, told as one beautiful story.',
    'intro'    => 'From the first haldi to the final vidaai, we photograph every function with a cinematic, unobtrusive eye — the big rituals, the quiet glances and the family moments you will want to keep forever.',
    'hero'     => $W . 'golden-hour-palace-wedding-walk.webp',
    'photos'   => 'Wedding',
    'meta'     => ['Coverage' => '1 – 4 days', 'Team' => '2 – 4 photographers', 'Delivery' => '4 – 6 weeks', 'Travel' => 'Destination weddings welcome'],
    'includes' => ['Haldi, mehndi, sangeet, wedding and reception coverage', 'Candid storytelling plus guided family and group portraits', 'Couple portrait session timed for golden hour', 'Detail shots — décor, outfits, jewellery, mehndi', 'A planning call and a shot list built around your rituals'],
    'deliver'  => ['500 – 1,500 hand-edited, colour-graded images', 'Private online gallery to view, download and share', 'Sneak-peek selection within 72 hours', 'Optional handcrafted wedding album'],
    'faq'      => [
      ['How far in advance should we book?', 'Peak-season dates (November – February) usually book 6 – 9 months ahead. Send your dates and we will confirm availability within two working days.'],
      ['Do you travel for destination weddings?', 'Yes — anywhere in India and abroad. Travel and stay are quoted separately and kept as simple as possible.'],
      ['Can we combine this with a pre-wedding shoot?', 'Absolutely. Most couples book both together, and it helps you get comfortable in front of the camera before the big day.'],
    ],
  ],

  'pre-wedding-shoots' => [
    'tagline'  => 'A love story, before the wedding story.',
    'intro'    => 'A relaxed, cinematic session at a location that means something to you — planned around light, outfits and the way you are together. No stiff poses, just you two.',
    'hero'     => $P . 'sunset-lakeside-palace-romance.webp',
    'photos'   => 'Pre-Wedding',
    'meta'     => ['Duration' => '4 – 8 hours', 'Looks' => '2 – 4 outfits', 'Delivery' => '2 – 3 weeks', 'Locations' => 'City, palace, lake, hills'],
    'includes' => ['Concept, moodboard and location planning', 'Outfit and styling guidance for each look', 'Golden-hour timing and gentle, natural direction', 'Multiple locations in one day where possible', 'Help with permissions for heritage locations'],
    'deliver'  => ['80 – 200 hand-edited images', 'Private online gallery', 'Social-media crops for save-the-dates', 'Optional short reel / slideshow'],
    'faq'      => [
      ['We feel awkward in front of a camera — is that okay?', 'That is completely normal. We start with walking, talking and laughing; most couples forget the camera within 15 minutes.'],
      ['Can you suggest locations?', 'Yes. We share a list of tried-and-tested spots for your city and style, with the best time of day for each.'],
    ],
  ],

  'portraits-headshots' => [
    'tagline'  => 'Portraits that look like you — on your best day.',
    'intro'    => 'Professional headshots and personal portraits in the studio or on location. Warm light, a calm pace and gentle direction so you look confident and natural.',
    'hero'     => $S . 'thoughtful-studio-portrait-in-navy-suit.webp',
    'pos'      => 'center 30%',
    'photos'   => 'Studio',
    'meta'     => ['Session' => '30 – 90 minutes', 'Looks' => '1 – 3 outfits', 'Delivery' => '5 – 7 days', 'Where' => 'Studio or your office'],
    'includes' => ['Pre-shoot call on purpose, style and wardrobe', 'Studio lighting with a choice of backdrops', 'Live preview so you can pick your favourites', 'Posing guidance for a relaxed, confident look', 'Team days for consistent company headshots'],
    'deliver'  => ['5 – 20 fully retouched images', 'Web, LinkedIn and print-ready sizes', 'Colour and black-and-white versions', 'Consistent look for the whole team'],
    'faq'      => [
      ['What should I wear?', 'Solid colours and well-fitted clothes work best. We send a short wardrobe guide after booking.'],
      ['Can you photograph our whole team at the office?', 'Yes — we bring a portable studio set-up and photograph everyone with the same light and backdrop.'],
    ],
  ],

  'maternity-newborn-family' => [
    'tagline'  => 'The small, fleeting chapters — kept forever.',
    'intro'    => 'Gentle, unhurried sessions at home or in the studio for expecting parents, newborns and families. Safety and comfort come first; beautiful pictures follow.',
    'photos'   => null,
    'meta'     => ['Session' => '1 – 3 hours', 'Where' => 'Home or studio', 'Delivery' => '2 weeks', 'Best time' => 'Maternity 28 – 34 wks · Newborn 5 – 15 days'],
    'includes' => ['Planning call and timing advice', 'Maternity gowns and props on request', 'Baby-safe posing with a calm, warm set-up', 'Family and sibling portraits', 'Plenty of breaks — we work at baby\'s pace'],
    'deliver'  => ['30 – 80 hand-edited images', 'Private online gallery', 'Print-ready files', 'Optional album or framed prints'],
    'shots'    => [
      ['The bump', 'Soft window light and flowing fabric to celebrate the last weeks of waiting.'],
      ['Tiny details', 'Fingers, toes, eyelashes — the details that change in a matter of days.'],
      ['First family portrait', 'Parents and baby together, calm and close, ready to frame.'],
      ['Siblings & grandparents', 'Gentle, playful frames of the people meeting the newest member.'],
    ],
    'faq'      => [
      ['When should we book a newborn session?', 'Ideally during pregnancy, with a tentative date. We adjust it once the baby arrives.'],
      ['Is the session safe for the baby?', 'Yes. We never force poses, keep the room warm and a parent is always within arm\'s reach.'],
    ],
  ],

  'events-corporate' => [
    'tagline'  => 'Your event, documented quietly and delivered fast.',
    'intro'    => 'Conferences, product launches, award nights, parties and corporate offsites — covered with discretion and delivered quickly, so your team can share while the moment is still fresh.',
    'photos'   => null,
    'meta'     => ['Coverage' => 'Hourly or full day', 'Team' => '1 – 3 photographers', 'Delivery' => 'Same day – 5 days', 'Where' => 'Anywhere in India'],
    'includes' => ['Stage, speakers and audience coverage', 'Candid networking and atmosphere shots', 'Branding, venue and décor details', 'Group photos and VIP portraits', 'Same-day highlights for social media'],
    'deliver'  => ['Edited images in web and print sizes', 'Same-day selection on request', 'Private online gallery for your team', 'Usage rights for marketing and PR'],
    'shots'    => [
      ['On stage', 'Speakers, panels and award moments, lit and framed for press.'],
      ['The room', 'Wide shots that show scale, energy and a full house.'],
      ['Candid networking', 'Real conversations and handshakes, never stiff line-ups.'],
      ['Brand & venue', 'Stage design, signage, décor and the details your team planned.'],
    ],
    'faq'      => [
      ['Can you deliver photos during the event?', 'Yes — we can edit and deliver a highlights set within hours for social media and press.'],
      ['Do you sign NDAs?', 'Of course. Confidential events and unreleased products are handled with full discretion.'],
    ],
  ],

  /* ---------------- Brands & Commercial ---------------- */
  'commercial-advertising' => [
    'tagline'  => 'Images that sell the feeling, not just the product.',
    'intro'    => 'Campaign photography for print, outdoor and digital — concept, production and post-production handled end to end, with one clear idea behind every frame.',
    'photos'   => null,
    'meta'     => ['Shoot' => '1 – 3 days', 'Scope' => 'Concept to final files', 'Delivery' => '1 – 2 weeks', 'Usage' => 'Licensed to your campaign'],
    'includes' => ['Creative brief, moodboard and shot list', 'Casting, locations and props coordination', 'Studio or on-location lighting set-ups', 'Art direction on set', 'High-end retouching and colour grading'],
    'deliver'  => ['Final campaign images in all required formats', 'Print, OOH and digital crops', 'Clear usage licence', 'Behind-the-scenes images on request'],
    'shots'    => [
      ['The hero image', 'One defining frame built around a single, clear idea.'],
      ['Lifestyle', 'Your product in real hands and real places, telling a story.'],
      ['Outdoor & print crops', 'Compositions planned with space for headlines and logos.'],
      ['Social cut-downs', 'Vertical and square variations made for feeds and stories.'],
    ],
    'faq'      => [
      ['Do you work with agencies?', 'Yes — we work directly with brands and with agencies, following your brief and brand guidelines.'],
      ['How is a campaign priced?', 'By shoot days, production needs and usage. Share your brief and we will send a detailed quote.'],
    ],
  ],

  'product-ecommerce' => [
    'tagline'  => 'Clean catalogue shots and hero images that stop the scroll.',
    'intro'    => 'Colour-accurate product photography for online stores and marketplaces, plus styled hero images for launches, ads and social media.',
    'photos'   => null,
    'meta'     => ['Volume' => '10 – 500+ products', 'Style' => 'White, styled or lifestyle', 'Delivery' => '5 – 10 days', 'Formats' => 'Amazon, Shopify, social'],
    'includes' => ['White-background catalogue images', 'Styled hero and lifestyle shots', 'Multiple angles and detail close-ups', 'Accurate colour matching to the real product', 'Batch pricing for large catalogues'],
    'deliver'  => ['Retouched, background-cleaned images', 'Marketplace-ready sizes and formats', 'Consistent look across the whole range', 'Fast re-shoots for new arrivals'],
    'shots'    => [
      ['Catalogue front', 'Clean white-background image that meets marketplace rules.'],
      ['Angles & scale', 'Side, back and in-hand shots that answer buyers\' questions.'],
      ['Macro details', 'Texture, stitching, finish and labels, sharp and true to colour.'],
      ['Styled hero', 'A sculptural, lit-for-mood image for launches and ads.'],
    ],
    'faq'      => [
      ['Do we need to send our products?', 'Yes — courier them to the studio and we return them after the shoot. For large or heavy items we can shoot on site.'],
      ['Do you meet Amazon / Flipkart image guidelines?', 'Yes, we deliver to the exact size, background and margin rules of each marketplace.'],
    ],
  ],

  'fashion-lookbooks' => [
    'tagline'  => 'Lookbooks and campaigns with a cinematic point of view.',
    'intro'    => 'Fashion, jewellery and accessories photographed with attention to fabric, fit and detail — in the studio or on location, art-directed to match your collection.',
    'photos'   => null,
    'meta'     => ['Shoot' => 'Half day – 2 days', 'Looks' => '10 – 60 per day', 'Delivery' => '1 – 2 weeks', 'Where' => 'Studio or location'],
    'includes' => ['Moodboard and look-by-look shot list', 'Model casting and hair & make-up coordination', 'Studio, location or mixed set-ups', 'Detail shots of fabric, embroidery and jewellery', 'Retouching that keeps texture real'],
    'deliver'  => ['Lookbook and e-commerce images', 'Campaign hero images', 'Social-media crops and formats', 'Optional behind-the-scenes content'],
    'shots'    => [
      ['Full look', 'Head-to-toe frames that show cut, fit and movement.'],
      ['Fabric & craft', 'Close-ups of embroidery, weave and embellishment.'],
      ['Editorial', 'Art-directed frames with a mood that matches the collection.'],
      ['Jewellery & accessories', 'Precise lighting that makes metal and stones sing.'],
    ],
    'faq'      => [
      ['Can you arrange models and stylists?', 'Yes — we have a trusted network of models, stylists and hair & make-up artists, or we can work with your team.'],
    ],
  ],

  'food-hospitality' => [
    'tagline'  => 'Dishes, spaces and atmosphere that make people want to visit.',
    'intro'    => 'Food, drinks, interiors and ambience for restaurants, cafés, hotels and cloud kitchens — styled and lit to look as good as they taste.',
    'photos'   => null,
    'meta'     => ['Shoot' => 'Half day – full day', 'Dishes' => '10 – 40 per day', 'Delivery' => '5 – 7 days', 'Where' => 'At your venue'],
    'includes' => ['Menu and dish photography', 'Interior and ambience shots', 'Chef and team portraits', 'Lifestyle shots with guests', 'Food styling guidance'],
    'deliver'  => ['Edited images for menus, Zomato / Swiggy and social', 'Website banners and print-ready files', 'Consistent look across every dish', 'Seasonal menu updates on request'],
    'shots'    => [
      ['Signature dishes', 'Styled and lit so the menu\'s stars look as good as they taste.'],
      ['Drinks & pours', 'Movement, condensation and colour that make people thirsty.'],
      ['The space', 'Interiors and ambience at the hour your venue looks its best.'],
      ['Behind the pass', 'Chefs, hands and kitchen action that tell your story.'],
    ],
    'faq'      => [
      ['Do you shoot during service hours?', 'We prefer quiet hours for dishes and interiors, then capture atmosphere during service if you want it.'],
    ],
  ],

  'architecture-interiors' => [
    'tagline'  => 'Spaces photographed with precision and natural light.',
    'intro'    => 'Architecture, interiors and real estate photographed with straight lines, true colours and the light the space was designed for.',
    'photos'   => null,
    'meta'     => ['Shoot' => '2 hours – full day', 'Spaces' => 'Homes, offices, hotels', 'Delivery' => '5 – 7 days', 'Extras' => 'Twilight & detail shots'],
    'includes' => ['Pre-visit to plan angles and timing', 'Perspective-corrected wide and detail shots', 'Day and twilight exteriors', 'Styling and decluttering guidance', 'Lifestyle shots with people on request'],
    'deliver'  => ['Edited, perspective-corrected images', 'Portfolio, website and award-entry sizes', 'Print-ready files', 'Licence for architects, designers and builders'],
    'shots'    => [
      ['The facade', 'Straight verticals and clean lines, timed for the best light.'],
      ['Room by room', 'Wide, natural views that show flow and proportion.'],
      ['Materials & details', 'Joinery, textures and fittings that show the craft.'],
      ['Twilight', 'Warm interiors against a blue-hour sky, the signature frame.'],
    ],
    'faq'      => [
      ['Can the image be shared by the architect and the interior designer?', 'Yes — we offer shared licences so every studio involved can use the images.'],
    ],
  ],

  /* ---------------- Add-ons ---------------- */
  'retouching-colour-grading' => [
    'tagline'  => 'A consistent, timeless finish for every image.',
    'intro'    => 'Professional editing for your photos — natural skin retouching, clean-ups and a signature colour grade that ties a whole gallery together.',
    'photos'   => [$W . 'serene-south-asian-bridal-portrait.webp', $S . 'warm-studio-portrait-smile.webp', $P . 'sunset-romance-at-the-historic-pavilion.webp', $W . 'bridal-dressing-in-warm-golden-light.webp'],
    'meta'     => ['Turnaround' => '2 – 7 days', 'Volume' => '1 – 2,000 images', 'Style' => 'Natural, cinematic grade', 'Files' => 'RAW or JPEG'],
    'includes' => ['Colour correction and signature grading', 'Natural skin retouching', 'Object and blemish removal', 'Background clean-up', 'Batch consistency across a full shoot'],
    'deliver'  => ['High-resolution edited files', 'Web and print versions', 'One round of revisions included', 'Before / after previews on request'],
    'faq'      => [
      ['Can you edit photos taken by another photographer?', 'Yes, as long as you have the rights to the images. RAW files give the best results.'],
    ],
  ],

  'albums-fine-art-prints' => [
    'tagline'  => 'Photographs you can hold, gift and hang on the wall.',
    'intro'    => 'Handcrafted wedding albums and archival fine-art prints, designed by us and finished with premium papers, covers and frames.',
    'hero'     => $W . 'henna-hands-and-golden-rings.webp',
    'photos'   => [$W . 'golden-palace-wedding-celebration.webp', $W . 'henna-hands-and-golden-rings.webp', $P . 'golden-hour-lakeside-romance.webp', $W . 'tearful-indian-wedding-sendoff.webp'],
    'meta'     => ['Album sizes' => '10×10 to 14×14 in', 'Pages' => '30 – 80 spreads', 'Delivery' => '3 – 5 weeks', 'Prints' => 'A4 to 40×60 in'],
    'includes' => ['Album design with your story and favourite images', 'Two rounds of layout revisions', 'Premium lay-flat pages', 'Leather, linen or photo covers', 'Archival prints, framed or unframed'],
    'deliver'  => ['Main album plus optional parent albums', 'Presentation box', 'Wall prints and frames', 'Delivery anywhere in India'],
    'faq'      => [
      ['Can we choose the photos for the album?', 'Yes — you shortlist your favourites from the online gallery and we design the album around them.'],
    ],
  ],
];
