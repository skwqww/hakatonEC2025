<script setup>

import NavList from '@/components/nav/NavList.vue'
import Preloader from '@/components/preloader/Preloader.vue'

import { usePreloaderStore } from '@/stores/PreloaderStore.js'


const preloaderStore = usePreloaderStore()


</script>

<template>
  <div class='relative min-h-screen bg-white'>
    <Preloader v-if="preloaderStore.preloaderStatus"/>

    <router-view class="pb-24" v-slot="{ Component }">
      <transition name="main">
        <component v-show="!preloaderStore.preloaderStatus" :is="Component" />
      </transition>
    </router-view>

<!--    <transition name="main">-->
<!--        <RouterView v-show="!preloaderStore.preloaderStatus"/>-->
<!--    </transition>-->

    <NavList class="fixed max-w-[500px] w-fit bottom-0"/>
  </div>
</template>

<style>

.main-enter-active{
  animation: main 0.5s
}


@keyframes main{
  0%{
    opacity: 0;
    transform: scale(0.9)
  }
  100%{
    transform: scale(1);
    opacity: 1
  }
}

</style>
