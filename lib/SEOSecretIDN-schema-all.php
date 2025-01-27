<!--SEOSecretIDN WebSite-->
<script type='application/ld+json' class='SEOSecretIDN'>
  {
    "@context": "https://schema.org",
    "@graph": [
    {
      "@type": "Organization",
      "@id": "#OrganizationWebSite",
      "url": "<?php echo $config['web']['url'];?>",
      "name": "<?php echo $data['short_title'];?>",
      "alternateName": "<?php echo $data['title'];?>",
      "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
      "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
      "sameAs": [
      "<?php echo $data['url_youtube']; ?>",
      "<?php echo $data['url_facebook']; ?>",
      "<?php echo $data['url_instagram']; ?>",
      "<?php echo $data['url_twitter']; ?>",
      "<?php echo $data['url_email']; ?>"
      ],
      "logo": {
      "@type": "ImageObject",
      "@id": "#LogoWebSite",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital512.png",
      "caption": "<?php echo $data['short_title'];?>"},
      "image": {
      "@type": "ImageObject",
      "@id": "#ImageWebSite",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
      "caption": "<?php echo $data['short_title'];?>"},
      "address": {
      "@type": "PostalAddress",
      "@id": "#PostalAddressWebSite",
      "streetAddress": "<?php echo $data['alamat'];?>",
      "addressLocality": "Padang",
      "addressRegion": "Sumatera Barat",
      "postalCode": "25133",
      "addressCountry": "Indonesia"},
      "review": {
      "@type": "Review",
      "@id": "#ReviewWebSite",
      "name": "Ulasan Reseller Produk",
      "author": "Kiki Lesvina",
      "description": "Harga layanan disini cukup murah bagi pemilik UMKM seperti saya, penggunaan aplikasi nya juga aman dan proses cepat. Sangat membantu sekali, terimakasih",
      "reviewRating": {
      "@type": "Rating",
      "ratingValue": "4.9",
      "worstRating": "1",
      "bestRating": "5"}
    }
  },
  {
    "@type": "NewsMediaOrganization",
    "@id": "#NewsMediaOrganizationWebSite",
    "url": "<?php echo $config['web']['url'];?>",
    "name": "<?php echo $data['short_title'];?>",
    "alternateName": "<?php echo $data['title'];?>",
    "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
    "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "sameAs": [
    "<?php echo $data['url_youtube']; ?>",
    "<?php echo $data['url_facebook']; ?>",
    "<?php echo $data['url_instagram']; ?>",
    "<?php echo $data['url_twitter']; ?>",
    "<?php echo $data['url_email']; ?>"
    ],
    "logo": {
    "@type": "ImageObject",
    "@id": "#LogoWebSite",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital512.png",
    "caption": "<?php echo $data['short_title'];?>"},
    "image": {
    "@type": "ImageObject",
    "@id": "#ImageWebSite",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
    "caption": "<?php echo $data['short_title'];?>"},
    "address": {"@id": "#PostalAddressWebSite"},
    "review": {"@id": "#ReviewWebSite"}
  },
  {
    "@type": "ImageObject",
    "@id": "#primaryImageOfPageWebSite",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
    "caption": "<?php echo $data['short_title'];?>"},
    {
      "@type": "WebSite",
      "@id": "#WebSite",
      "url": "<?php echo $config['web']['url'];?>",
      "name": "<?php echo $data['short_title'];?>",
      "alternateName": "<?php echo $data['title'];?>",
      "headline": "<?php echo $data['short_title'];?>",
      "alternativeHeadline": "<?php echo $data['title'];?>",
      "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
      "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
      "inLanguage": "id-ID",
      "about": {
      "@id": "#OrganizationWebSite"},
      "publisher": {
      "@id": "#NewsMediaOrganizationWebSite"},
      "potentialAction": [
      {
        "@type": "SearchAction",
        "target": "<?php echo $config['web']['url'];?>home?q={q}",
        "query-input": "required name=q"}
        ]
      }
      ]

    }
    ]
  }
</script>

<!--SEOSecretIDN LocalBusiness-->
<script type='application/ld+json' class='SEOSecretIDN'>
  {
    "@context": "https://schema.org/",
    "@type": "LocalBusiness",
    "@id": "#LocalBusiness",
    "url": "https://kitadigital.my.id",
    "name": "KITADIGITAL Group",
    "alternateName": "KITADIGITAL Group Indonesia",
    "description": "KITADIGITAL Group adalah perusahaan yang memiliki spesialisasi dalam bidang teknologi, pengembangan software, kecerdasan buatan, internet, portal media online, digital marketing, teknologi finansial dan e-commerce di Indonesia.",
    "disambiguatingDescription": "KITADIGITAL Group adalah perusahaan yang memiliki spesialisasi dalam bidang teknologi, pengembangan software, kecerdasan buatan, internet, portal media online, digital marketing, teknologi finansial dan e-commerce di Indonesia. Berawal dari sebuah organisasi komunitas pada tahun 2009, KITADIGITAL Group saat ini berdiri sebagai perusahaan yang memiliki 20+ startup penyedia produk, layanan jasa, dan portal media informasi online.",
    "priceRange": "$",
    "hasMap": "",
    "openingHours": "Sa 12:00 AM-12:00 AM, Su 12:00 AM-12:00 AM",
    "telephone": "+62 82377823390",
    "geo": {
    "@type": "GeoCoordinates",
    "latitude": -0.9003501,
    "longitude": 100.3385632
  },
  "sameAs": [
  "https://instagram.com/kitadigital",
  "https://kitadigital.my.id",
  "mailto:hello@kitadigital.id"
  ],
  "logo": {
  "@type": "ImageObject",
  "@id": "#LogoWebSite",
  "inLanguage": "id-ID",
  "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/partner.png",
  "caption": "KITADIGITAL Group Indonesia"},
  "image": {
  "@type": "ImageObject",
  "@id": "#ImageWebSite",
  "inLanguage": "id-ID",
  "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/partner.png",
  "caption": "KITADIGITAL Group Indonesia"},
  "address": {
  "@type": "PostalAddress",
  "@id": "#PostalAddressLocalBusiness",
  "streetAddress": "KITADIGITAL Group, Jl. Sumbawa, Ulak Karang Utara, Kec. Padang Utara",
  "addressLocality": "Kota Padang",
  "addressRegion": "Sumatera Barat",
  "postalCode": "25133",
  "addressCountry": "Indonesia"},
  "aggregateRating": {
  "@type": "AggregateRating",
  "ratingValue": "4.9",
  "reviewCount": "4<?php echo date('j');?>"},
  "review": {
  "@type": "Review",
  "@id": "#ReviewLocalBusiness",
  "name": "Ulasan Reseller Produk",
  "author": "Kiki Lesvina",
  "description": "Harga layanan disini cukup murah bagi pemilik UMKM seperti saya, penggunaan aplikasi nya juga aman dan proses cepat. Sangat membantu sekali, terimakasih",
  "reviewRating": {
  "@type": "Rating",
  "ratingValue": "4.9",
  "worstRating": "1",
  "bestRating": "5"}
}
}
</script>

<!--SEOSecretIDN Software-->
<script type='application/ld+json' class='SEOSecretIDN'>
  {
    "@context": "https://schema.org/",
    "@type": "SoftwareApplication",
    "@id": "#Software<?php echo $config['web']['url'];?>",
    "url": "<?php echo $config['web']['url'];?>",
    "name": "<?php echo $data['short_title'];?>",
    "alternateName": "<?php echo $data['title'];?>",
    "headline": "<?php echo $data['title'];?>",
    "alternativeHeadline": "<?php echo $data['short_title'];?>",
    "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
    "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "inLanguage": "id-ID",
    "keywords": "<?php echo $data['short_title']; ?>, Voucher indodax, Auto subscribe yt, Auto follower ig, Panel SMM indo, Server pulsa h2h, Top up game, SMM <?php echo $data['short_title']; ?>",    
    "abstract": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "image": {
    "@type": "ImageObject",
    "@id": "#ImageObjectSoftware<?php echo $config['web']['url'];?>",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
    "caption": "<?php echo $data['title'];?>"},
    "applicationCategory": "https://schema.org/BusinessApplication",
    "operatingSystem": "Windows, Android, iOS, OSX, Java, Symbian",
    "offers": {
    "@type": "Offer",
    "@id": "#OfferSoftware<?php echo $config['web']['url'];?>",
    "url": "<?php echo $config['web']['url'];?>",
    "availability": "https://schema.org/InStock",
    "priceCurrency": "IDR",
    "itemCondition": "https://schema.org/UsedCondition",
    "priceValidUntil": "2020-12-12",
    "price": "0"},
    "aggregateRating": {
    "@type": "AggregateRating",
    "@id": "AggregateRatingSoftware",
    "ratingValue": "4.9",
    "reviewCount": "<?php echo date('j');?>4"},
    "review": {
    "@type": "Review",
    "@id": "ReviewSoftware",
    "name": "Ulasan Pengguna Aplikasi",
    "author": "Kiki Lesvina",
    "description": "Aman dan proses cepat. Sangat membantu sekali, terimakasih kitadigital",
    "reviewRating": {
    "@type": "Rating",
    "@id": "#RatingSoftware",
    "ratingValue": "4.9",
    "worstRating": "1",
    "bestRating": "5"}
  },
  "potentialAction": {
  "@type": "ReadAction",
  "@id": "ReadActionSoftware",
  "target": {
  "@type": "EntryPoint",
  "@id": "EntryPointSoftware",
  "actionPlatform": [
  "https://schema.org/DesktopWebPlatform",
  "https://schema.org/IOSPlatform",
  "https://schema.org/AndroidPlatform"]}
}
}
}
</script>

<!--SEOSecretIDN WebPage-->
<script type='application/ld+json' class='SEOSecretIDN'>
  {
    "@context": "https://schema.org",
    "@graph": [
    {
      "@type": "Organization",
      "@id": "#OrganizationWebSite",
      "url": "<?php echo $config['web']['url'];?>",
      "name": "<?php echo $data['short_title'];?>",
      "alternateName": "<?php echo $data['title'];?>",
      "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
      "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
      "sameAs": [
      "<?php echo $data['url_youtube']; ?>",
      "<?php echo $data['url_facebook']; ?>",
      "<?php echo $data['url_instagram']; ?>",
      "<?php echo $data['url_twitter']; ?>",
      "<?php echo $data['url_email']; ?>"
      ],
      "logo": {
      "@type": "ImageObject",
      "@id": "#LogoWebSite",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital512.png",
      "caption": "<?php echo $data['short_title'];?>"},
      "image": {
      "@type": "ImageObject",
      "@id": "#ImageWebSite",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
      "caption": "<?php echo $data['short_title'];?>"},
      "address": {
      "@type": "PostalAddress",
      "@id": "#PostalAddressWebSite",
      "streetAddress": "<?php echo $data['alamat'];?>",
      "addressLocality": "Padang",
      "addressRegion": "Sumatera Barat",
      "postalCode": "25133",
      "addressCountry": "Indonesia"},
      "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.9",
      "reviewCount": "<?php echo date('j');?>4"},
      "review": {
      "@type": "Review",
      "@id": "#ReviewWebSite",
      "name": "Ulasan Reseller Produk",
      "author": "Kiki Lesvina",
      "description": "Harga layanan disini cukup murah bagi pemilik UMKM seperti saya, penggunaan aplikasi nya juga aman dan proses cepat. Sangat membantu sekali, terimakasih kitadigital",
      "reviewRating": {
      "@type": "Rating",
      "ratingValue": "4.9",
      "worstRating": "1",
      "bestRating": "5"}
    }
  },
  {
    "@type": "NewsMediaOrganization",
    "@id": "#NewsMediaOrganizationWebSite",
    "url": "<?php echo $config['web']['url'];?>",
    "name": "<?php echo $data['short_title'];?>",
    "alternateName": "<?php echo $data['title'];?>",
    "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
    "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "sameAs": [
    "<?php echo $data['url_youtube']; ?>",
    "<?php echo $data['url_facebook']; ?>",
    "<?php echo $data['url_instagram']; ?>",
    "<?php echo $data['url_twitter']; ?>",
    "<?php echo $data['url_email']; ?>"
    ],
    "logo": {
    "@type": "ImageObject",
    "@id": "#LogoWebSite",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital512.png",
    "caption": "<?php echo $data['short_title'];?>"},
    "image": {
    "@type": "ImageObject",
    "@id": "#ImageWebSite",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
    "caption": "<?php echo $data['short_title'];?>"},
    "address": {"@id": "#PostalAddressWebSite"},
    "review": {"@id": "#ReviewWebSite"}
  },
  {
    "@type": "WebSite",
    "@id": "#WebSiteWebSite",
    "url": "<?php echo $config['web']['url'];?>",
    "name": "<?php echo $data['short_title'];?>",
    "alternateName": "<?php echo $data['title'];?>",
    "headline": "<?php echo $data['short_title'];?>",
    "alternativeHeadline": "<?php echo $data['title'];?>",
    "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
    "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "inLanguage": "id-ID",
    "publisher": {
    "@id": "#OrganizationWebSite"},
    "potentialAction": [
    {
      "@type": "SearchAction",
      "@id": "#SearchActionWebSite",
      "target": "<?php echo $config['web']['url'];?>home?q={q}",
      "query-input": "required name=q"}
      ]
    },
    {
      "@type": "ImageObject",
      "@id": "#primaryImageOfPageWebSite",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
      "caption": "<?php echo $data['short_title'];?>"},
      {
        "@type": "WebPage",
        "@id": "#WebPageWebSite",
        "url": "<?php echo $config['web']['url'];?>",
        "name": "<?php echo $data['short_title'];?>",
        "alternateName": "<?php echo $data['title'];?>",
        "headline": "<?php echo $data['short_title'];?>",
        "alternativeHeadline": "<?php echo $data['title'];?>",
        "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
        "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
        "inLanguage": "id-ID",
        "isPartOf": {
        "@id": "#WebSiteWebSite"},
        "about": {
        "@id": "#OrganizationWebSite"},
        "publisher": {
        "@id": "#NewsMediaOrganizationWebSite"},
        "primaryImageOfPage": {
        "@id": "#primaryImageOfPageWebSite"},
        "potentialAction": [
        {
          "@type": "ReadAction",
          "target": ["<?php echo $config['web']['url'];?>home?q={q}"]
        }
        ]

      }
      ]
    }
  </script>

  <!--SEOSecretIDN Product-->
  <script type='application/ld+json' class='SEOSecretIDN'>
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "@id": "#Product<?php echo $config['web']['url'];?>",
      "url": "<?php echo $config['web']['url'];?>",
      "name": "<?php echo $data['title'];?>",
      "alternateName": "<?php echo $data['short_title'];?>",
      "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
      "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
      "sku": "kitadigital",
      "mpn": "KMPV1",
      "identifier": "KMV1",
      "image": {
      "@type": "ImageObject",
      "@id": "#ImageObjectProduct<?php echo $config['web']['url'];?>",
      "inLanguage": "id-ID",
      "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
      "caption": "<?php echo $data['title'];?>"},
      "logo": {
      "@type": "ImageObject",
      "@id": "#ImageObjectProduct<?php echo $config['web']['url'];?>"},
      "offers": {
      "@type": "Offer",
      "@id": "#OfferProduct<?php echo $config['web']['url'];?>",
      "url": "<?php echo $config['web']['url'];?>",
      "availability": "https://schema.org/InStock",
      "priceCurrency": "IDR",
      "itemCondition": "https://schema.org/UsedCondition",
      "priceValidUntil": "2020-12-12",
      "price": "0"},
      "aggregateRating": {
      "@type": "AggregateRating",
      "@id": "#AggregateRatingProduct<?php echo $config['web']['url'];?>",
      "ratingValue": "4.9",
      "reviewCount": "<?php echo date('j');?>4"},
      "review": {
      "@type": "Review",
      "@id": "#ReviewProduct<?php echo $config['web']['url'];?>",
      "name": "Ulasan Reseller Produk",
      "author": "Kiki Lesvina",
      "description": "Harga layanan disini cukup murah bagi pemilik UMKM seperti saya, penggunaan aplikasi nya juga aman dan proses cepat. Sangat membantu sekali, terimakasih kitadigital",
      "reviewRating": {
      "@type": "Rating",
      "@id": "#RatingProduct<?php echo $config['web']['url'];?>",
      "ratingValue": "4.9",
      "worstRating": "1",
      "bestRating": "5"}
    },
    "brand": {
    "@type": "brand",
    "@id": "#brandProduct<?php echo $config['web']['url'];?>",
    "url": "<?php echo $config['web']['url'];?>",
    "name": "<?php echo $data['title'];?>",
    "alternateName": "<?php echo $data['short_title'];?>",
    "description": "<?php echo $data['short_title'];?> - <?php echo $data['deskripsi_web'];?>",
    "disambiguatingDescription": "<?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB dan Layanan Perbankan",
    "image": {
    "@type": "ImageObject",
    "@id": "#ImageObjectProduct<?php echo $config['web']['url'];?>",
    "inLanguage": "id-ID",
    "url": "<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital.png",
    "caption": "<?php echo $data['short_title'];?>"},
    "logo": {
    "@type": "ImageObject",
    "@id": "#ImageObjectProduct<?php echo $config['web']['url'];?>"},
    "sameAs": [
    "<?php echo $data['url_youtube']; ?>",
    "<?php echo $data['url_facebook']; ?>",
    "<?php echo $data['url_instagram']; ?>",
    "<?php echo $data['url_twitter']; ?>",
    "<?php echo $data['url_email']; ?>"
    ]
  }
}
</script>