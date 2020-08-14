<template>
  <div class="search-container">
    <div id="search-trigger" class="text-brown-500 hover:text-orange-400mr-2 cursor-pointer hover:text-red-500" @click="toggleSearch">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" class="w-8 h-8">
        <title>Search</title>
        <path d="M21.9 18.3c1.2-1.8 1.9-4 1.9-6.4C23.8 5.3 18.5 0 11.9 0 5.3 0 0 5.3 0 11.9s5.3 11.9 11.9 11.9c2.4 0 4.6-.7 6.5-1.9l.5-.4 8.5 8.5 2.6-2.7-8.5-8.5.4-.5zM18.5 5.2c1.8 1.8 2.8 4.1 2.8 6.6s-1 4.9-2.8 6.6-4.1 2.8-6.6 2.8-4.9-1-6.6-2.8-2.8-4.1-2.8-6.6 1-4.9 2.8-6.6 4.1-2.8 6.6-2.8 4.9 1.1 6.6 2.8z" fill="currentColor" />
      </svg>
    </div>

    <div id="search-form" class="bg-orange-400 absolute top-0 left-0 w-screen h-screen z-50" v-show="displaySearch">
      <a class="details__close m-16 block font-bold text-22 md:text-28 text-black hover:text-red-500 text-right" href="#close" @click.prevent="toggleSearch">X</a>

      <div class="center-vertical w-full">
        <div class="w-8/12 mx-auto">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
  export default {
    data() {
      return {
        displaySearch: false
      }
    },
    methods: {
      toggleSearch() {
        this.displaySearch = !this.displaySearch;

        if (this.displaySearch) {
          this.disableScrolling();

          this.$nextTick(() => {
            document.getElementById("search-form-input").focus();
          });
        }
        else {
          this.enableScrolling();
        }
      },
      disableScrolling() {
        this.scrollPosition = window.pageYOffset;

        const $body = document.querySelector('body');
        $body.style.overflow = 'hidden';
        $body.style.position = 'fixed';
        $body.style.top = `-${this.scrollPosition}px`;
        $body.style.width = '100%';
      },
      enableScrolling() {
        const $body = document.querySelector('body');
        $body.style.removeProperty('overflow');
        $body.style.removeProperty('position');
        $body.style.removeProperty('top');
        $body.style.removeProperty('width');

        window.scrollTo(0, this.scrollPosition);
      },
    },
    created() {
      const onEscape = (e) => {
        if (this.displaySearch && e.keyCode === 27) {
          this.displaySearch = false;
          this.enableScrolling();
        }
      }

      document.addEventListener('keydown', onEscape)

      this.$once('hook:destroyed', () => {
        document.removeEventListener('keydown', onEscape)
      })
    }
  }
</script>
