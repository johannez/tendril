<template>
  <div class="news-listing-container max-w-content mx-auto">
    <div class="news mb-8 md:flex" v-for="i in newsItems">
      <div class="news__image flex-none md:mr-8 mb-4" v-if="i.image">
        <picture>
          <img :src="i.image.sources.std" :srcset="i.image.sources.lg + ' 2x'" :alt="i.image.alt" class="">
        </picture>
      </div>
      <div class="text-wrapper flex-initial">
        <div class="news__date">{{ i.date }}</div>
        <div class="news__title font-header text-22 md:text-26 leading-tight mb-4">
          <a :href="i.link">{{ i.title }}</a>
        </div>
        <div class="news__summary">{{ i.summary }}</div>
      </div>
    </div>

    <pager :pagination="pagination"></pager>
  </div>
</template>


<script>
  export default {
    props: {
      pageSize: {
        type: Number,
        default: 20
      }
    },
    data() {
      return {
        newsItems: [],
        pagination: []
      }
    },
    mounted() {
      try {
        // Load data from 
        axios
          .get('/wp-json/api/news' + location.search)
          .then(response => {
            this.newsItems = response.data.news_items;
            if (response.data.pagination) {
              this.pagination = response.data.pagination;
            }
          })
      }
      catch (err) {
        console.log(err);
      }
    }
  }
</script>
