<meta name="title"
      content="{{ $post->title }}">

@if($post->content)
    <meta name="description" content="{{ Str::limit($post->content, 30) }}">
    <meta property="og:description"
          content="{{ Str::limit($post->content, 30) }}"/>
    <meta name="twitter:description" content="{{ Str::limit($post->content, 30) }}">
@endif

<meta name="author" content="{{ !empty(settings('company_name')? settings('company_name'): env('APP_NAME')) }}">
<meta property="og:site_name" content="{{ !empty(settings('company_name')? settings('company_name'): env('APP_NAME')) }}">
<meta property="og:title" content="{{ $post->title }}">


<meta property="og:url"
      content="{{ url()->current() }}"/>
<meta property="og:type"
      content="article"/>

    <meta property="og:image"
          content="{{ get_featured_image($post->featured_image) }}"/>
    <meta property="og:image:secure_url"
          content="{{ get_featured_image($post->featured_image) }}"/>
<meta name="twitter:site" content="@">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->title }}">
