<template>
  <div class="faq-container" :class="containerClass">
    <div class="faq__question flex justify-between cursor-pointer" :class="questionClass" @click="toggle()">
      <slot name="question"></slot>
      <div class="icon__closed" v-show="!showAnswer">
        <slot name="icon-closed">+</slot>
      </div>
      <div class="icon__open" v-show="showAnswer">
        <slot name="icon-open">-</slot>
      </div>
    </div>
    <div class="faq__answer" :class="answerClass" v-show="showAnswer">
      <slot name="answer"></slot>
    </div>
  </div>
</template>



<script>
  export default {
    props: {
      containerClass: '',
      questionClass: '',
      answerClass: ''
    },
    data() {
      return {
        showAnswer: false
      }
    },
    methods: {
      toggle() {
        this.showAnswer = !this.showAnswer;
      }
    }
  }
</script>
