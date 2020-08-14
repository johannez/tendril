<template>
  <li class="menu-item " :id="['menu-item-id-' + item.id]" :class="['border-t border-' + colors.border]">
    <div v-if="item.children && item.children.length > 0" class="flex justify-between items-center h-12">
      <a :href="item.url" class="block px-6" :class="['text-' + colors.font, 'hover:text-' + colors.font]">{{ item.text }}</a>
      <div @click="toggleSubMenu" class="expander self-center w-16 h-full cursor-pointer text-center" :class="['border-l border-' + colors.border, 'text-' + colors.border, expanderBackground]">
        <svg class="transform mx-auto" :class="[expanderRotation]" width="14" height="44" viewBox="0 0 42 80" xmlns="http://www.w3.org/2000/svg"><path d="M1 78l36.8-38L1 2.133 2.2 0 41 40 2.2 80z" fill="currentColor" stroke="currentColor" fill-rule="evenodd"/></svg>
      </div>
    </div>
    <a v-else :href="item.url" class="block py-2 px-6" :class="['text-' + colors.font, 'hover:text-' + colors.font]">{{ item.text }}</a>  
    
    <ul v-if="item.children && item.children.length > 0" class="menu" :class="['menu-level-' + (level + 1), 'bg-' + colors.bgSecondary, { hidden: hideSubMenu }]">
      <menu-mobile-item v-for="child in item.children" :key="child.id" :item="child" :level="level + 1"></menu-mobile-item>
    </ul>
  </li>
</template>

<script>
  export default {
    name: "menuMobileItem",
    props: {
      item: Object,
      level: Number
    },
    data() {
      return {
        colors: this.$parent.colors,
        hideSubMenu: true,
        expanderBackground: 'bg-transparent',
        expanderRotation: 'rotate-90'
      }
    },
    methods: {
      toggleSubMenu() {
        this.hideSubMenu = !this.hideSubMenu;
        this.expanderBackground = this.hideSubMenu ? 'bg-transparent' : 'bg-' + this.$parent.colors.bgSecondary;
        this.expanderRotation = this.hideSubMenu ? 'rotate-90' : '-rotate-90';
      }
    }
  };
</script>
