<template>
  <div class="menu-container">
    <button @click.prevent="toggleMenu" class="navbar-burger bg-transparent hover:bg-transparent flex items-center border rounded px-0 h-auto leading-none" :class="['text-' + colors.trigger, 'hover:text-' + colors.trigger]">
      <svg class="fill-current h-8 w-8" :class="{ hidden: displayMenu }" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <title>Menu Open</title>
        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
      </svg>
      <svg class="fill-current h-6 w-8" :class="[{ hidden: !displayMenu }]" viewBox="0 0 298.667 298.667">
        <title>Menu Close</title>
        <path d="M298.667 30.187L268.48 0 149.333 119.147 30.187 0 0 30.187l119.147 119.146L0 268.48l30.187 30.187L149.333 179.52 268.48 298.667l30.187-30.187L179.52 149.333z" fill="currentColor"/>
      </svg>
    </button>

    <div class="absolute right-0 w-full md:w-1/2 h-screen overflow-scroll" :class="['bg-' + colors.bgPrimary, { hidden: !displayMenu }]" :style="{ top: offset }">

      <div class="search-form-container">
        <form name="search" role="search" method="get" id="searchform-mobile" class="searchform border-t" action="/">

          <div class="form-item search-field flex justify-center mx-auto py-2 w-3/4">
            
            <input id="search-form-input-mobile" type="text" ref="search" value="" name="s" placeholder="Search" aria-label="Search" required tabindex="-1" class="border border-gray-300">
        
            <button type="submit" class="bg-transparent text-gray-300 p-4 border-t border-r border-b border-solid border-gray-300" id="search-submit" aria-label="Submit">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" class="w-4 h-4">
                <title>Search</title>
                <path d="M21.9 18.3c1.2-1.8 1.9-4 1.9-6.4C23.8 5.3 18.5 0 11.9 0 5.3 0 0 5.3 0 11.9s5.3 11.9 11.9 11.9c2.4 0 4.6-.7 6.5-1.9l.5-.4 8.5 8.5 2.6-2.7-8.5-8.5.4-.5zM18.5 5.2c1.8 1.8 2.8 4.1 2.8 6.6s-1 4.9-2.8 6.6-4.1 2.8-6.6 2.8-4.9-1-6.6-2.8-2.8-4.1-2.8-6.6 1-4.9 2.8-6.6 4.1-2.8 6.6-2.8 4.9 1.1 6.6 2.8z" fill="currentColor" />
              </svg>
            </button>
          </div>
        </form>
      </div>
      
      <ul class="menu menu-level-0 text-lg mb-48 border-b">
        <menu-mobile-item v-for="mi in menuItems" :key="mi.id" :item="mi" :level="0"></menu-mobile-item>
      </ul> 
    </div>
  </div>
</template>

<script>
  import MenuMobileItem from './MenuMobileItem.vue';

  export default {
    props: {
      menuItems: {
        type: Array,
        required: true
      },
      colors: {
        type: Object,
        default() {
          return {
            font: 'black',
            border: 'gray-300',
            bgPrimary: 'white',
            bgSecondary: 'gray-200',
            trigger: 'black'
          }
        }
      },
      offset: {
        type: String,
        default: '4rem'
      }
    },
    data() {
      return {
        displayMenu: false
      }
    },
    components: {
      MenuMobileItem
    },
    methods: {
      toggleMenu() {
        this.displayMenu = !this.displayMenu;

        if (this.displayMenu) {
          this.disableScrolling();
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
    }
  };
</script>
