<template>
  <div class="event-listing-container max-w-content mx-auto">
    <div class="event-filters border-b pb-3 text-right">
      <a :href="path" :class="['hover:text-red-500', filterUpcomingClass]">Upcoming Events</a>
      <span>|</span>
      <a :href="path + '?past_events=1'" :class="['hover:text-red-500', filterPastClass]">Past Events</a>
    </div>

    <div class="event md:flex border-b py-8" v-for="i in events">
      <div class="event__image flex-none md:w-pro-slid md:mr-8 mb-4 md:mb-0" v-if="i.image">
        <picture>
          <img :src="i.image.sources.std" :srcset="i.image.sources.lg + ' 2x'" :alt="i.image.alt" class="">
        </picture>
      </div>
      <div class="text-wrapper flex-initial">
        <div class="event__title font-header font-bold text-xl md:text-22 lg:text-26 leading-tight mb-2" :class="{'md:mt-4': i.image}">
          <a :href="i.link">{{ i.title }}</a>
        </div>
        <div class="event__summary">{{ i.summary }}</div>
      </div>
    </div>

    <div v-if="!events.length" class="no-results">No events.</div>

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
        events: [],
        pagination: [],
        filterPastEvents: false,
        path: location.pathname
      }
    },
    computed: {
      filterUpcomingClass() {return this.filterPastEvents ? 'text-red-500' : 'text-black'},
      filterPastClass() {return this.filterPastEvents ? 'text-black' : 'text-red-500'}
    },
    mounted() {
      try {
        let searchParams = new URLSearchParams(location.search);
        
        if (searchParams.has('past_events')) {
          this.filterPastEvents = searchParams.get('past_events') == 1;
        }
        // Load data from 
        axios
          .get('/wp-json/api/events' + location.search)
          .then(response => {
            console.log('hello');
            this.events = response.data.events;
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
