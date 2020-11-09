<style>
    .lf-featured-post-1, .lf-featured-post-2 {
        position: relative;
        width: 100%;
    }

    .lf-featured-post-1::before, .lf-featured-post-2::before {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        top: 0;
        left: 0;
        z-index: 1;
    }

    .lf-featured-post-1 > a, .lf-featured-post-2 > a {
        z-index: 2;
        display: block;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: transparent;
    }

    .lf-featured-post-1 {
        height: 450px;
    }

    .lf-featured-post-2 {
        height: 223px;
    }

    .lf-featured-post-img {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        object-fit: cover;
    }

    .lf-featured-post-1 .lf-featured-post-content, .lf-featured-post-2 .lf-featured-post-content {
        position: absolute;
        bottom: 10px;
        left: 0;
        width: 100%;
        z-index: 2;
    }

    .lf-featured-post-1 .lf-featured-post-title .title {
        font-size: 24px;
        line-height: 1.5;
        text-transform: capitalize;
    }

    .lf-featured-post-2 .lf-featured-post-title .title {
        font-size: 16px;
        line-height: 1.5;
        font-weight: 400;
        text-transform: capitalize;
    }

    .lf-featured-post-2 .lf-featured-post-terms {
        font-size: 12px;
    }

    .comment-item.sub-comment {
        margin-left: 60px;
    }

    .comment-content {
        width: 100%;
    }

    @media only screen and (max-width: 767px) {
        .lf-featured-post-1, .lf-featured-post-2 {
            height: 350px;
        }
    }
    .post-item-list {
        border-bottom: 1px solid;
        padding: 20px 0;
    }

    .post-item-list:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .post-item-list:first-child {
        padding-top: 0;
    }

    .post-widget-content ul li {
        list-style: none;
        padding: 15px 0;
        border-bottom: 1px solid;
    }

    .post-widget-content ul li:first-child {
        padding-top: 0;
    }

    .post-widget-content ul li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
</style>
