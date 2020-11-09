<style>
    body {
        line-height: 1.8;
        background-color: #090909;
    }
    body.light .bg-secondary {
        background-color: #ffffff !important;
    }
    .bg-black {
        background-color: #000000;
    }
    .lf-toggle-bg-content {
        background-color: #090909 !important;
    }
    body.light .bg-black{
        background-color: #ffffff !important;
    }
    .section-padding {
        padding: 100px 0;
    }
    svg, svg .st0 {
        fill: #ffffff;
    }
    body.light svg, body.light svg .st0 {
        fill: #1d1d1d;;
    }

    .text-danger {
        color: #f1045c !important;
    }
    .bg-danger, .btn-danger {
        background-color: #f1045c !important;
    }
    .border-danger, .btn-danger {
        border-color: #f1045c !important;
    }
    .border-3 {
        border: 3px solid;
    }
    .section-title .title {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .section-title .title::before {
        width: 180px;
        height: auto;
        border-top: 2px solid #f1045c;
        content: "";
        position: absolute;
        left: 50%;
        bottom: 0;
        margin-left: -90px;
    }
    .section-title.text-left .title::before {
        left: 0;
        margin-left: 0;
    }
    .tm-banner {
        padding: 120px 0;
        background-image: url("{{ get_regular_site_image('banner.jpg') }}");
        background-repeat: no-repeat;
        background-position: top left;
        background-size: cover;
    }
    .btn-lg {
        font-weight: 600;
        font-size: 18px;
        padding: 10px 20px;
    }
    .tm-tab-nav li {
        position: relative;
        z-index: 1;
    }
    .tm-tab-nav li::before {
        content: "";
        position: absolute;
        width: 100%;
        border-top: 1px solid #313131;
        top: 50%;
        left: 50%;
        z-index: -1;
    }
    .tm-tab-nav li:last-child::before {
        border-top: none;
    }
    .tm-feature-tab-nav {
        padding: 1rem;
        margin: auto;
        display: flex;
        border: 1px solid #313131;
        background-color: #000000;
        height: 160px;
        width: 160px;
        text-align: center;
        overflow: hidden;
        position: relative;
    }
    body.light .tm-tab-nav li::before, body.light .tm-feature-tab-nav{
        border-color: #cccccc;
    }
    body.light .tm-feature-tab-nav {
        background-color: #ffffff;
    }
    .tm-feature-tab-nav.active, .tm-feature-tab-nav:hover {
        background-color: #f1045c !important;
        border: none;
        color: #ffffff !important;
    }
    .tm-feature-tab-nav.active::before {
        content: "";
        position: absolute;
        height: 24px;
        width: 24px;
        background-color: #000000;
        transform: rotate(45deg);
        bottom: 0;
        left: 50%;
        margin-left: -12px;
        margin-bottom: -12px;
    }
    body.light .tm-feature-tab-nav.active::before {
        background-color: #ffffff;
    }
    .tm-feature-tab-nav.active svg, .tm-feature-tab-nav.active svg .st0, .tm-feature-tab-nav:hover svg, .tm-feature-tab-nav:hover svg .st0 {
        fill: #ffffff;
    }
    .tm-investment-card {
        cursor: pointer;
        max-width: 400px;
        margin: 10px auto;
    }
    .card-icon {
        position: absolute;
        top: -35px;
        width: 100%;
        left: 0;
    }
    .tm-investment-card-icon {
        font-size: 32px;
        line-height: 64px;
        width: 64px;
        height: 64px;
        text-align: center;
        border-radius: 50%;
        position: relative;
    }
    .tm-investment-card .tm-investment-card-icon::after {
        content: "";
        width: 80px;
        height: 80px;
        border: 3px solid #f1045c;
        display: block;
        position: absolute;
        left: 50%;
        margin-left: -40px;
        top: 50%;
        margin-top: -40px;
        border-radius: 50%;
        transition: ease-in-out 0.45s;
        opacity: 0;
    }
    .tm-investment-card:hover .tm-investment-card-icon::after {
        opacity: 1;
    }
    .fill-red {
        fill: #f1045c;
    }
    .team-item {
        cursor: pointer;
        max-width: 380px;
        margin:1rem auto;
    }
    .team-content {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        background-color: rgba(0,0,0,0.75);
        padding: 1rem;
        opacity: 0;
        visibility: hidden;
        transition: ease-in-out 0.5s;
    }
    .team-item:hover .team-content{
        visibility: visible;
        opacity: 1;
    }
    .tm-testimonial-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .blockquote .fa-quote-left {
        font-size: 84px;
        opacity: 0.5;
    }
    .team-content .social-media-links li {
        display: inline-block;
        margin: 8px;
        font-size: 24px;
    }
    .team-content .social-media-links li a {
        color: #ffffff;
    }
    .tm-news-read-more-btn::before {
        content: "";
        width: 40px;
        height: auto;
        border-top: 2px solid #f1045c;
        margin: auto 10px auto 0;
    }
    .tm-news-content {
        margin-left: -150px;
        padding-left: 180px;
        z-index: 0;
    }
    .tm-news-img-area {
        z-index: 1;
    }
    @media only screen and (max-width: 767px) {
        .title.font-size-48, .title.font-size-44 {
            font-size: 36px !important;
        }
        .title.font-size-30{
            font-size: 24px !important;
        }
        .tm-tab-nav li::before {
            width: auto;
            height: 100%;
            border-right: 1px solid;
        }
        .tm-tab-nav li:last-child::before {
            border-right: none;
        }
        .blockquote .fa-quote-left {
            font-size: 54px;
        }
        .tm-news-content {
            margin-left: auto;
            padding: 15px !important;
        }
    }
</style>
