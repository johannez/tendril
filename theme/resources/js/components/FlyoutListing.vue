<template>
  <div class="flyout-container">
    <div class="md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-4 xl:gap-8 ms-flex-4">

      <div :class="gi.classes" v-for="(gi, index) in gridItems" v-show="itemDisplay(gi)">
        <div class="card border h-full hover:bg-orange-400" :class="{'bg-orange-400': gi.isActive}" v-if="gi.type == 'card'" @click="showDetails(gi.id, index)">
          <div class="flyout__image">
            <picture v-if="gi.image">
              <img :src="gi.image.person" :srcset="gi.image.person + ', ' + gi.image.person_lg + ' 2x'" :alt="gi.image.alt" class="">
            </picture>
            <img v-else src="//dummyimage.com/450x450.jpg">
          </div>
          <div class="text-wrapper p-4" >
            <div class="flyout__title font-header font-bold text-22 md:text-24 lg:text-28 leading-tight flex items-center">
              <div>{{ gi.title }}</div>
              <div class="text-40 ml-2 -mt-1">
                <div :class="{'hidden': !gi.isActive}">-</div>
                <div :class="{'hidden': gi.isActive}">+</div>
              </div>
            </div>
            <div class="flyout__intro" v-if="gi.intro">{{ gi.intro }}</div>
          </div>
        </div>

        <div class="details relative" v-if="gi.type == 'details'">
          <a class="details__close block font-bold text-22 md:text-28 text-black text-right" href="#close" @click.prevent="hideDetails()">X</a>
          <div class="details__title font-header mb-2 md:mb-0 text-26 md:text-32 lg:text-40 leading-tight lg:leading-none">{{ gi.title }}</div>
          <div class="details__intro text-xl md:text-22 leading-tight">{{ gi.intro }}</div>
          <div class="details__content mt-8" v-html="gi.body"></div>
        </div>

      </div>

    </div>
  </div>
</template>



<script>
  export default {
    props: {
      flyouts: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        gridItems: [],
        cardClasses: 'flyout cursor-pointer mb-4 md:mb-0',
        detailsClasses: 'details-container border-4 border-orange-400 mb-4 md:mb-0 px-4 md:px-10 pb-4 md:pb-10 pt-3 md:pt-6 '
      }
    },
    methods: {
      itemDisplay(item) {
        let show = true;

        if (item.type == 'details') {
          show = item.isActive;
        }

        return show;
      },
      showDetails(flyoutId, index) {
        // There can only be one open at all times.
        this.hideDetails();

        // Calculate which details container to use.
        let detailsContainerIndex = parseInt((flyoutId - 1) * 2) + 1;
        
        if (screen.width >= 768 && screen.width < 1024) {
          detailsContainerIndex = 2 + (parseInt((flyoutId - 1) / 2) * 3);
        }
        else if (screen.width >= 1024) {
          detailsContainerIndex = 5 + (parseInt((flyoutId - 1) / 4) * 6);
        }

        // In case the last row is not full.
        if (detailsContainerIndex + 1 > this.gridItems.length) {
          detailsContainerIndex = this.gridItems.length - 1;
        }

        // Fill the details container with the data.
        this.gridItems[detailsContainerIndex].title = this.flyouts[flyoutId - 1].title;
        this.gridItems[detailsContainerIndex].intro = this.flyouts[flyoutId - 1].intro;
        this.gridItems[detailsContainerIndex].body = this.flyouts[flyoutId - 1].details;

        
        this.gridItems[index].isActive = true;
        this.gridItems[detailsContainerIndex].isActive = true;
      },
      hideDetails() {
        // console.log(index);
        this.gridItems.forEach(gi => {
          gi.isActive = false;
        });

        // this.gridItems[index].isActive = false;
      },
      fillGridItems() {
        // Mark all flyouts as inactive.
        this.flyouts.forEach((fo, index) => {
          fo.isActive = false;
          fo.type = 'card';
          fo.classes = this.cardClasses;
          fo.id = index + 1;
          this.gridItems.push(fo);
          
          if (screen.width < 768) {
            this.gridItems.push({
              type: 'details',
              isActive: false,
              classes: this.detailsClasses
            }); 
          }
          else {
            if ((index + 1) % 2 == 0 || (index + 1) == this.flyouts.length) {
              this.gridItems.push({
                type: 'details',
                isActive: false,
                classes: this.detailsClasses + (screen.width < 1024 ? 'col-span-2' : 'col-span-4 ms-w-full'),
                title: '',
                intro: '',
                body: ''
              }); 
            }
          }
        });
      }
    },
    mounted() {
      this.fillGridItems(); 
    }
  }
</script>
