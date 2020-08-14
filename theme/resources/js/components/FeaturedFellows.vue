<template>
  <div class="featured-fellows-container">
    <div class="image-container justify-center hidden md:flex">
      <div 
        v-for="fellow in fells" 
        class="fellow__image relative" 
        :class="[{'is-active': fellow.isActive}, {'is-disabled': fellow.isDisabled}, {'-top-2': fellow.isActive}]"
        @mouseover="selectFellow(fellow, true)"
        @mouseleave="selectFellow(fellow, false)"
        >
        <a :href="fellow.link">
          <picture v-if="fellow.image">
            <img :src="fellow.image.person_featured" :srcset="fellow.image.person_featured_lg + ' 2x'" :alt="fellow.image.alt" class="">
          </picture>
          <img v-else src="//dummyimage.com/320x620.jpg" alt="placeholder">
          <div class="image-filter opacity-0" :class="{'opacity-100': fellow.isDisabled}"></div>
          <div class="image-active bg-orange-400 h-8 opacity-0 absolute left-0 -bottom-2 w-full text-black text-right pr-4 transition duration-500 ease-in-out" :class="{'opacity-100': fellow.isActive}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 19" class="w-6 h-6 inline-block">
              <title>Arrow Right</title>
              <path d="M17-.1l19.1 9.2-18.9 9.6-.1-7.9-15.6.2H0V8h1.5L17 7.8V-.1z" fill="currentColor"/>
            </svg>
          </div>
        </a>
      </div>
    </div>

    <div class="text-container hidden md:block -mt-16 text-white bg-blue-700 pt-20 pb-6 px-10 min-h-14">
      <div v-for="fellow in fellows" class="fellow__copy flex max-w-3xl mx-auto" v-show="fellow.isActive">
        <div class="fellow__name text-26 md:text-32 lg:text-40 leading-tight md:w-64">{{ fellow.name }}</div>
        <div class="fellow__meta flex-1 pl-8 pt-3">
          <div class="fellow__years-study mb-3">Fellowship Awarded {{ fellow.start_year }}</div>
          <div class="fellow__description">{{ fellow.description }}</div>
        </div>
      </div>
    </div>  
  </div>
</template>


<script>
  export default {
    props: {
      fellows: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        fells: []
      }
    },
    methods: {
      selectFellow(selectedFellow, active) {
        if (screen.width >= 768) {
          this.fells.forEach(fellow => {
            if (active) {
              if (fellow.id == selectedFellow.id) {
                fellow.isActive = true;
                fellow.isDisabled = false;
              }
              else {
                fellow.isDisabled = true;
              }
            }
            else {
              fellow.isActive = false;
              fellow.isDisabled = false;
            }
          });
        }
      }
    },
    mounted() {
      // Fill up the data fellows.
      this.fellows.forEach(fellow => {
        fellow.isActive = false;
        fellow.isDisabled = false;
        this.fells.push(fellow);
      });
    }
  }
</script>
