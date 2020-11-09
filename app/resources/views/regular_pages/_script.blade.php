<script>
    "use strict";

    var testimonial = new Vue({
        el: "#tm-testimonial",
        data: {
            number : 0,
            totalTestimonial: 0,
            testimonialsData: [
                {
                    avatar: "{{ get_regular_site_image('client-1.jpg') }}",
                    client: "Mark Robert Smith",
                    clientRole: "Chief Executive Officer",
                    message: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum."
                },
                {
                    avatar: "{{ get_regular_site_image('team-1.jpg') }}",
                    client: "Client Robert Smith",
                    clientRole: "Chief Executive Officer",
                    message: "Consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum. Adipisci aliquid consequatur eum hic odio."
                },
                {
                    avatar: "{{ get_regular_site_image('team-2.jpg') }}",
                    client: "Jonathan Smith",
                    clientRole: "Chief Executive Officer",
                    message: "Adipisci aliquid consequatur Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum."
                }
            ],
            activeTestimonial: []
        },
        mounted: function () {
            this.totalTestimonial = this.testimonialsData.length;
            if(this.totalTestimonial > 0)
            {
                this.activeTestimonial = this.testimonialsData[0];
            }
        },
        methods: {
            next: function () {
                if(this.number >= this.totalTestimonial-1)
                {
                    this.number = 0
                }
                else {
                    this.number++
                }
                this.activeTestimonial = this.testimonialsData[this.number];
            },
            prev: function () {
                if(this.number <= 0)
                {
                    this.number = this.totalTestimonial-1
                }
                else {
                    this.number--
                }

                this.activeTestimonial = this.testimonialsData[this.number];
            }
        }
    });

    var news = new Vue({
        el: "#tm-latest-news",
        data: {
            number : 0,
            totalNews: 0,
            newsData: [
                {
                    avatar: "{{ get_regular_site_image('team-1.jpg') }}",
                    author: "Mark Robert Smith",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 18,
                    month: "FEB",
                    message: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum.",
                },
                {
                    avatar: "{{ get_regular_site_image('team-2.jpg') }}",
                    author: "Client Robert Smith",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 28,
                    month: "FEB",
                    message: "Consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum. Adipisci aliquid consequatur eum hic odio."
                },
                {
                    avatar: "{{ get_regular_site_image('team-3.jpg') }}",
                    author: "Jonathan Smith",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 29,
                    month: "JAN",
                    message: "Adipisci aliquid consequatur Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid consequatur eum hic odio, odit placeat quia sed sit voluptatum."
                }
            ],
            activeNews: []
        },
        mounted: function () {
            this.totalNews = this.newsData.length;
            if(this.totalNews > 0)
            {
                this.activeNews = this.newsData[0];
            }
        },
        methods: {
            next: function () {
                if(this.number >= this.totalNews-1)
                {
                    this.number = 0
                }
                else {
                    this.number++
                }
                this.activeNews = this.newsData[this.number];
            },
            prev: function () {
                if(this.number <= 0)
                {
                    this.number = this.totalNews-1
                }
                else {
                    this.number--
                }

                this.activeNews = this.newsData[this.number];
            }
        }
    });
</script>
