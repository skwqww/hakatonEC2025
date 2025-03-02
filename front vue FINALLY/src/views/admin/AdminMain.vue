<script setup>

import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';

import Preloader from '@/components/preloader/Preloader.vue'

import { usePreloaderStore } from '@/stores/PreloaderStore.js'

import { useRouter } from 'vue-router'

const router = useRouter()

const preloaderStore = usePreloaderStore()


</script>

<template>
  <div>
    <h1 class="pt-4 ml-8 font-semibold text-2xl mb-4">Панель администратора</h1>
    <Tabs value="0">
      <TabList>
        <Tab @click="router.push('/admin/users')" value="0">
          <router-link class="h-full w-full" to="/admin/users">Пользователи</router-link>
        </Tab>
        <Tab @click="router.push('/admin/chats')" value="1">
          <router-link class="h-full w-full" to="/admin/chats">Чаты</router-link>
        </Tab>

      </TabList>
    </Tabs>
    <div class="px-5 mt-3">

      <router-view v-slot="{ Component }">
        <transition name="main">
          <component v-show="!preloaderStore.adminPreloaderStatus" :is="Component" />
        </transition>
      </router-view>

<!--      <transition name="main">-->
<!--        <div v-show="!preloaderStore.adminPreloaderStatus">-->
<!--          <router-view></router-view>-->
<!--        </div>-->
<!--      </transition>-->
      <Preloader v-if="preloaderStore.adminPreloaderStatus"/>
    </div>
  </div>
</template>

<style scoped>

</style>
