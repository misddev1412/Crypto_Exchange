<div class="lf-social-networks-wrapper lf-toggle-bg-card lf-toggle-border-color border p-3 clearfix">
    <div class="pull-left px-2">
        <h3 class="my-2 mb-0">{{ __('Share This Post') }}</h3>
    </div>
    <div class="links pull-right px-2">
        <a class="btn lf-toggle-border border" href="https://www.facebook.com/sharer.php?u={{ urlencode($url) }}&amp;t={{ urlencode($title) }}" target="_blank">
            <i class="fa fa-facebook"></i>
        </a>
        <a class="btn lf-toggle-border border" href="https://twitter.com/share?text={{ urlencode($title) }}&amp;url={{ urlencode($url) }}" target="_blank" >
            <i class="fa fa-twitter"></i>
        </a>
        <a class="btn lf-toggle-border border" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode($url) }}&amp;title={{ url($title) }}&amp;" target="_blank">
            <i class="fa fa-linkedin"></i>
        </a>
        <a class="btn lf-toggle-border border" href="mailto:?subject={{ urlencode($title) }}&amp;body={{ urlencode($url) }}" target="_self" rel="noopener noreferrer">
            <i class="fa fa-envelope"></i>
        </a>
    </div>
</div>
