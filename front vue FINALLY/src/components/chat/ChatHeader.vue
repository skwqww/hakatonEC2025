<script setup>

import { useChatStore } from '@/stores/ChatStore.js'
import { onMounted } from 'vue'

import { useRoute } from 'vue-router'

const route = useRoute()

const chatStore = useChatStore()

onMounted(() => {
  chatStore.setChatName(route.params.chatId)
})

</script>

<template>
  <div class="flex items-center gap-5 bg-mainBlack px-8 py-3">
    <router-link to="/chats"><i class="pi pi-arrow-left w-[20px] text-white cursor-pointer"></i></router-link>
    <div class="bg-mainElems rounded-xl w-[40px] h-[40px]"></div>
    <h2 class="text-lg font-medium text-white">{{chatStore.chatName}}</h2>
  </div>
</template>

<style scoped>

</style>
