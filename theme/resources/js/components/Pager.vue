<template>
  <div class="pager-container">

    <div class="flex flex-wrap justify-center">
      <div class="" v-if="typeof pagination.prev !== 'undefined' && pagination.prev !== '' && pagination.prev !== null">
        <a :href="pagination.prev.link" class="pager__prev" :class="prevClasses">Prev</a>
      </div>

      <div class="px-4 mb-4" v-for="page in pagination.pages">
        
        <a :href="page.link" v-if="page.link" class="pager__link" :class="pageLinkClasses">{{ page.title }}</a>
      
        <span class="pager__current" :class="pageCurrentClasses" v-else-if="page.current">{{ page.title }}</span>

        <span class="pager__dots" v-else v-html="page.title"></span>
      </div>

      <div class="" v-if="typeof pagination.next !== 'undefined' && pagination.next !== '' && pagination.next !== null">
        <a :href="pagination.next.link" class="pager__next" :class="nextClasses">Next</a>
      </div>
    </div>

  </div>
</template>


<script>
  export default {
    props: {
      pagination: {
        required: true,
        default: null
      }
    },
    data() {
      return {
        page: 1,
        pagerItemClasses: 'block p-4 hover:text-black',
        prevClasses: 'hover:text-black mr-8',
        nextClasses: 'hover:text-black ml-8',
        pageLinkClasses: 'hover:text-black',
        pageCurrentClasses: 'text-black'
      }
    }
  }
</script>
