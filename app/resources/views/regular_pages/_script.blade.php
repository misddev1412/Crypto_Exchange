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
                    clientRole: "Chief Technical Officer",
                    message: "Veteran investor Bill Miller says that all major banks, investment banks, and high net worth firms will eventually have some exposure to Bitcoin or something like it. He said that Bitcoin’s staying power is getting better every day."
                },
                {
                    avatar: "{{ get_regular_site_image('team-1.jpg') }}",
                    client: "Client Robert Smith",
                    clientRole: "Chief Executive Officer",
                    message: "Veteran investor Bill Miller says that all major banks, investment banks, and high net worth firms will eventually have some exposure to Bitcoin or something like it. He said that Bitcoin’s staying power is getting better every day."
                },
                {
                    avatar: "{{ get_regular_site_image('team-2.jpg') }}",
                    client: "Jonathan Smith",
                    clientRole: "Chief Executive Officer",
                    message: "Veteran investor Bill Miller says that all major banks, investment banks, and high net worth firms will eventually have some exposure to Bitcoin or something like it. He said that Bitcoin’s staying power is getting better every day."
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
                    author: "Marco Jon. D",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 18,
                    month: "FEB",
                    message: "If someone ask you what the emergence of cryptocurrency would contribute to the financial world, for sure the main thing that will cross your mind would be what is cryptocurrency? Primarily this would be the first thought of most people especially those who are not familiar with the currently existing digital currencies. However, if you are someone who’s knowledgeable enough with regards to cryptocurrencies, even with your eyes closed, for sure you can answer the question very well.",
                },
                {
                    avatar: "{{ get_regular_site_image('team-2.jpg') }}",
                    author: "Client Robert Smith",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 28,
                    month: "FEB",
                    message: "If someone ask you what the emergence of cryptocurrency would contribute to the financial world, for sure the main thing that will cross your mind would be what is cryptocurrency? Primarily this would be the first thought of most people especially those who are not familiar with the currently existing digital currencies. However, if you are someone who’s knowledgeable enough with regards to cryptocurrencies, even with your eyes closed, for sure you can answer the question very well.",
                },
                {
                    avatar: "{{ get_regular_site_image('team-3.jpg') }}",
                    author: "Jonathan Smith",
                    authorRole: "Chief Executive Officer",
                    title : "How to Cryptocurrency Begun and It's Impact To Financial Transactions? ",
                    url: "#",
                    date: 29,
                    month: "JAN",
                    message: "If someone ask you what the emergence of cryptocurrency would contribute to the financial world, for sure the main thing that will cross your mind would be what is cryptocurrency? Primarily this would be the first thought of most people especially those who are not familiar with the currently existing digital currencies. However, if you are someone who’s knowledgeable enough with regards to cryptocurrencies, even with your eyes closed, for sure you can answer the question very well.",
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
